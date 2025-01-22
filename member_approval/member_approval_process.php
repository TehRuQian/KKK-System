<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}
if ($_SESSION['u_type'] != 1) {
    header('Location: ../login.php');
    exit();
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

// Generate a secure token and set an expiry time
$token = bin2hex(random_bytes(16)); // Generates a random 16-byte token
$expiryTime = date("Y-m-d H:i:s", strtotime("+10 minutes", time())); // Expiry time set to 10 minutes from now

// Store the token and expiry time in the database
$sql = "UPDATE tb_user SET reset_token = ?, token_expiry = ? WHERE u_id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, 'ssi', $token, $expiryTime, $uid);
mysqli_stmt_execute($stmt);

// Generate the password reset link with the token as a query parameter
$resetLink = "http://localhost/KKK-System/reset_password.php?token=$token";

// Function to send an email for setting password
function sendApprovalEmail($email, $name, $memberNo, $temporaryPassword, $resetLink) {
    $subject = "Tahniah! Keanggotaan Anda Telah Diluluskan";
    $message = "
    <html>
    <body>
        <p>Encik/Puan {$name},</p>
        <p>Tahniah! Applikasi anda sebagai anggota Koperasi Kakitangan KADA telah diterima!</p>
        <p>Berikut ialah butiran log masuk sementara anda:</p>
        <ul>
            <li><strong>Username/No. Anggota:</strong> {$memberNo}</li>
            <li><strong>Kata Laluan Sementara:</strong> {$temporaryPassword}</li>
        </ul>
        <p>Sila gunakan pautan berikut untuk log masuk dan menukar kata laluan anda dengan segera:</p>
        <p><a href='$resetLink'>Pautan Pertukaran Kata Laluan</a></p>
        <p>Sekian, Terima Kasih.</p>
        <p>Tech-Hi-Five</p>
    </body>
    </html>";

    $headers = "MIME-Version: 1.0\r\n" . 
               "Content-Type: text/html; charset=UTF-8\r\n" .
               "From: no_reply@kada.com\r\n" . 
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
            if (sendApprovalEmail($email, $name, $memberNo, $temporaryPassword, $resetLink)) {
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