<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}
if ($_SESSION['u_type'] != 1) {
    header('Location: ../login.php');
    exit();
}

include '../db_connect.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$emailMessage = ""; // Variable for email notification message

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_members']) && isset($_POST['action'])) {
    $selectedMembers = $_POST['selected_members'];
    $action = $_POST['action'];
    $uid = $_SESSION['u_id'];
    $currentDate = date('Y-m-d H:i:s');
    
    // Begin transaction for batch update
    mysqli_begin_transaction($con);
    try {
        foreach ($selectedMembers as $applicationId) {
            $applicationId = intval($applicationId);  // Ensure integer value for SQL query
            if ($action === 'approve') {
                // Fetch member details to get necessary info for approval
                $sqlGetMember = "SELECT * FROM tb_member WHERE m_memberApplicationID = $applicationId";
                $result = mysqli_query($con, $sqlGetMember);
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $memberEmail = $row['m_email'];  // Get member's email
                    $memberName = $row['m_name'];

                    // Get the next available member number by finding the last member number
                    $sqlGetLastMemberNo = "SELECT MAX(m_memberNo) AS lastMember FROM tb_member";
                    $lastResult = mysqli_query($con, $sqlGetLastMemberNo);
                    $lastRow = mysqli_fetch_assoc($lastResult);
                    $nextMemberNo = $lastRow['lastMember'] + 1;  // Calculate the next available member number

                    // Update member status to approved and add member number
                    $sqlMem = "UPDATE tb_member
                               SET m_adminID = '$uid', m_status = '3', m_approvalDate = '$currentDate', m_memberNo = '$nextMemberNo'
                               WHERE m_memberApplicationID = '$applicationId'";
                    mysqli_query($con, $sqlMem);

                    // Insert financial details for the new member
                    $sqlFin = "INSERT INTO tb_financial (f_memberNo, f_shareCapital, f_feeCapital, f_fixedSaving, f_memberFund, f_memberSaving, f_dateUpdated)
                               VALUES ('$nextMemberNo', 0, 0, 0, 0, 0, '$currentDate')";
                    mysqli_query($con, $sqlFin);

                    // Generate temporary password for the member
                    $tempPassword = generateTemporaryPassword();
                    $hashedPassword = password_hash($tempPassword, PASSWORD_DEFAULT);

                    // Create login credentials for the new member
                    $token = generateResetToken();
                    $expiryTime = date('Y-m-d H:i:s', strtotime('+1 hour')); // Expiry time for token
                    $sqlUser = "INSERT INTO tb_user (u_id, u_pwd, u_type, reset_token, token_expiry)
                                VALUES ('$nextMemberNo', '$hashedPassword', 2, '$token', '$expiryTime')";
                    mysqli_query($con, $sqlUser);

                    // Send approval email
                    if (!empty($memberEmail)) {
                        $subject = "Tahniah! Keanggotaan Anda Telah Diluluskan";
                        $message = "
                        <html>
                        <body>
                            <p>Encik/Puan {$memberName},</p>
                            <p>Tahniah! Permohonan anda telah diluluskan sebagai anggota Koperasi Kakitangan KADA.</p>
                            <p>Berikut adalah butiran log masuk sementara anda:</p>
                            <ul>
                                <li><strong>Username/No. Anggota:</strong> {$nextMemberNo}</li>
                                <li><strong>Kata Laluan Sementara:</strong> {$tempPassword}</li>
                            </ul>
                            <p>Sila gunakan pautan berikut untuk menetapkan kata laluan baru anda:</p>
                            <p><a href='http://localhost/KKK-System/reset_password.php?token={$token}'>Pautan Tetapan Kata Laluan</a></p>
                            <p>Selamat datang dan terima kasih.</p>
                        </body>
                        </html>";

                        $headers = "MIME-Version: 1.0\r\n" . 
                                   "Content-Type: text/html; charset=UTF-8\r\n" . 
                                   "From: noreply@kkk.com\r\n" . 
                                   "X-Mailer: PHP/" . phpversion();

                        if (mail($memberEmail, $subject, $message, $headers)) {
                            $emailMessage = "E-mel telah dihantar kepada anggota.";
                        } else {
                            $emailMessage = "Kelulusan permohonan berjaya diproses, tetapi gagal menghantar e-mel.";
                            error_log("Gagal menghantar e-mel kepada $memberEmail");
                        }
                    } else {
                        $emailMessage = "Kelulusan permohonan berjaya diproses, tetapi tiada e-mel anggota yang tersedia.";
                    }
                }
            } elseif ($action === 'reject') {
                // Only update status to rejected, no email needed
                $sqlReject = "UPDATE tb_member 
                              SET m_adminID = '$uid', m_status = '2', m_approvalDate = '$currentDate' 
                              WHERE m_memberApplicationID = '$applicationId'";
                mysqli_query($con, $sqlReject);
                $emailMessage = "Permohonan telah ditolak.";
            }
        }

        // Commit the transaction if all queries succeed
        mysqli_commit($con);
        header('Location: member_approval.php?success=1&message=' . urlencode($emailMessage));
        exit();
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        mysqli_roll_back($con);
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    // Redirect back if POST data is not set or invalid
    header('Location: member_approval.php');
    exit();
}

mysqli_close($con);

function generateTemporaryPassword($length = 8) {
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*'), 0, $length);
}

function generateResetToken() {
    return bin2hex(random_bytes(16));  // Generate a random token for password reset link
}
?>
