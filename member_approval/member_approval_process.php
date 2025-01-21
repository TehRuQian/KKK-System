<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../header_admin.php';
include '../db_connect.php';

// Retrieve data
$uid = $_SESSION['u_id'];
$mstatus = $_POST['mstatus'];
$mApplicationID = $_POST['mApplicationID'];
$currentDate = date('Y-m-d H:i:s');
$mMemberNo = isset($_POST['mMemberNo']) ? $_POST['mMemberNo'] : null;

// Create a temporary and hashed password
function generateTemporaryPassword($length = 8) {
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*'), 0, $length);
}

$temporaryPassword = generateTemporaryPassword();
$hashedPassword = password_hash($temporaryPassword, PASSWORD_DEFAULT);
$resetLink = "http://127.0.0.1/KKK-System/reset_password.php?token=$token";

// Function to send an email for setting password
function sendApprovalEmail($email, $name, $memberNo, $temporaryPassword) {
    $subject = "Tahniah! Keanggotaan Anda Telah Diluluskan";
    $message = "Encik/Puan {$name},\n\n" .
               "Tahniah! Applikasi anda sebagai anggota Koperasi Kakitangan KADA telah diterima!\n\n" .
               "Berikut ialah butiran log masuk sementara anda:\n" .
               "- Username/No. Anggota: {$memberNo}\n" .
               "- Kata Laluan Sementara: {$temporaryPassword}\n\n" .
               "Sila gunakan pautan berikut untuk log masuk dan menukar kata laluan anda dengan segera:\n" .
               "<a href=$resetLink>$resetLink</a>\n\n" .
               "Sekian, Terima Kasih.\n\n" .
               "Tech-Hi-Five";

    $headers = "From: no_reply@kada.com\r\n" .
               "Reply-To: hello@gmail.com\r\n" .
               "X-Mailer: PHP/" . phpversion();

    return mail($email, $subject, $message, $headers);
}

// Approval & Rejection
if ($mstatus == 3 && $mMemberNo) {
    // Approval
    $sqlMem = "UPDATE tb_member
             SET m_adminID = '$uid', m_status = '$mstatus', m_approvalDate = '$currentDate', m_memberNo = '$mMemberNo'
             WHERE m_memberApplicationID = $mApplicationID";

    $sqlFin = "INSERT INTO tb_financial (f_memberNo, f_shareCapital, f_feeCapital, f_fixedSaving, f_memberFund, f_memberSaving, f_dateUpdated)
             VALUES ('$mMemberNo', 0, 0, 0, 0, 0, '$currentDate')";

    $sqlUser = "INSERT INTO tb_user (u_id, u_pwd, u_type)
                VALUES ('$mMemberNo', '$hashedPassword', 2)";

    // Execute queries
    if (mysqli_query($con, $sqlMem) && mysqli_query($con, $sqlFin) && mysqli_query($con, $sqlUser)) {
        // Fetch member email and name (Email Sending Purpose)
        $sqlFetch = "SELECT m_email, m_name, m_memberNo FROM tb_member WHERE m_memberApplicationID = $mApplicationID";
        $result = mysqli_query($con, $sqlFetch);
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $email = $row['m_email'];
            $name = $row['m_name'];
            $memberNo = $row['m_memberNo'];

            if (!$email) {
                error_log("Email is empty or not found for member ID $mApplicationID");
            }

            // Send the approval email
            if (sendApprovalEmail($email, $name, $memberNo, $temporaryPassword)) {
                echo "<script>
                        alert('Kelulusan anggota berjaya diproses! E-mel telah dihantar kepada anggota.');
                        window.location.href = 'member_approval.php';
                      </script>";
            } else {
                error_log("Failed to send email to $email");
                echo "<script>
                        alert('Kelulusan anggota berjaya diproses, tetapi gagal menghantar e-mel.');
                        window.location.href = 'member_approval.php';
                      </script>";
            }
        }
    } else {
        echo "<script>
                alert('Ralat memproses kelulusan anggota: " . mysqli_error($con) . "');
                window.history.back();
              </script>";
    }
} elseif ($mstatus == 2) {
    // Rejection
    $sqlReject = "UPDATE tb_member
                  SET m_adminID = '$uid', m_status = '$mstatus', m_approvalDate = '$currentDate'
                  WHERE m_memberApplicationID = $mApplicationID";

    if (mysqli_query($con, $sqlReject)) {
        echo "<script>
                alert('Applikasi anggota telah berjaya ditolak.');
                window.location.href = 'member_approval.php';
              </script>";
    } else {
        echo "<script>
                alert('Ralat menolak applikasi anggota: " . mysqli_error($con) . "');
                window.history.back();
              </script>";
    }
} else {
    echo "<script>
            alert('Tindakan tidak sah.');
            window.history.back();
          </script>";
}

// Close Connection
mysqli_close($con);
?>
