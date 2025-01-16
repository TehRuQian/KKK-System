<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../header_admin.php';
include '../db_connect.php';

// Retrieve data from form
$uid = $_SESSION['u_id'];
$mstatus = $_POST['mstatus'];
$mApplicationID = $_POST['mApplicationID'];
$currentDate = date('Y-m-d H:i:s');
$mMemberNo = isset($_POST['mMemberNo']) ? $_POST['mMemberNo'] : null;

// Create a temporary password for the user
function generateTemporaryPassword($length = 8) {
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*'), 0, $length);
}

$temporaryPassword = generateTemporaryPassword();
$hashedPassword = password_hash($temporaryPassword, PASSWORD_DEFAULT);

// Function to send an email
function sendApprovalEmail($email, $name, $memberNo, $temporaryPassword) {
    $subject = "Tahniah! Keanggotaan Anda Telah Diluluskan";
    $message = "Encik/Puan {$name},\n\n" .
               "Tahniah! Applikasi anda sebagai anggota Koperasi Kakitangan KADA telah diterima!\n\n" .
               "Berikut ialah butiran log masuk sementara anda:\n" .
               "- Username/No. Anggota: {$memberNo}\n" .
               "- Kata Laluan Sementara: {$temporaryPassword}\n\n" .
               "Sila gunakan pautan berikut untuk log masuk dan menukar kata laluan anda dengan segera:\n" .
               "[Pautan ke Halaman Tukar Kata Laluan]\n\n" .
               "Sekian, Terima Kasih.\n\n" .
               "Tech-Hi-Five";
    
    $headers = "From: no_reply@kada.com\r\n" .
               "Reply-To: hello@gmail.com\r\n" .
               "X-Mailer: PHP/" . phpversion();

    return mail($email, $subject, $message, $headers);
}

// SQL Update Operation
if ($mstatus == 3 && $mMemberNo) {
    $sql1 = "UPDATE tb_member
             SET m_adminID = '$uid', m_status = '$mstatus', m_approvalDate = '$currentDate', m_memberNo = '$mMemberNo'
             WHERE m_memberApplicationID = $mApplicationID";

    $sql2 = "INSERT INTO tb_financial (f_memberNo, f_shareCapital, f_feeCapital, f_fixedSaving, f_memberFund, f_memberSaving, f_alBai, f_alInnah, f_bPulihKenderaan, f_roadTaxInsurance, f_specialScheme, f_specialSeasonCarnival, f_alQadrulHassan, f_dateUpdated)
             VALUES ('$mMemberNo', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '$currentDate')";

    $sql3 = "INSERT INTO tb_user (u_id, u_pwd, u_type)
                VALUES ('$mMemberNo', '$hashedPassword', 2)";

    // Execute queries
    if (mysqli_query($con, $sql1) && mysqli_query($con, $sql2) && mysqli_query($con, $sql3)) {
        // Fetch member email and name
        $sqlFetch = "SELECT m_email, m_name, m_memberNo FROM tb_member WHERE m_memberApplicationID = $mApplicationID";
        $result = mysqli_query($con, $sqlFetch);
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $email = $row['m_email'];
            $name = $row['m_name'];
            $memberNo = $row['m_memberNo'];

            // Send the approval email
            if (sendApprovalEmail($email, $name, $memberNo, $temporaryPassword)) {
                echo "<script>
                        alert('Member approval processed successfully! An email has been sent to the member.');
                        window.location.href = 'member_approval.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Member approval processed successfully, but failed to send email.');
                        window.location.href = 'member_approval.php';
                      </script>";
            }
        }
    } else {
        echo "<script>
                alert('Error processing member approval: " . mysqli_error($con) . "');
                window.history.back();
              </script>";
    }
}

// Close Connection
mysqli_close($con);
?>
