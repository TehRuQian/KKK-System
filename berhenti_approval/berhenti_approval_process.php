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
$uid = $_SESSION['u_id']; // ID of the user performing the action
$status = $_POST['bstatus']; // Status selected in the form
$applicationId = $_POST['tdApplicationID']; // Application ID from the form
$currentDate = date('Y-m-d H:i:s'); // Current timestamp
$reviewComments = isset($_POST['tdUlasan']) ? $_POST['tdUlasan'] : null; // Admin's comments, if provided

// If the status is 'Dilulus', retrieve or validate member number
if ($status == 2) {
    $ulasan = isset($_POST['tdUlasan']) ? $_POST['tdUlasan'] : null;
    if (!$ulasan) {
        echo "<script>alert('Ulasan diperlukan untuk proses menolak!'); window.location.href='berhenti_approval.php';</script>";
        exit;
    }
}

$sqlGetMemberNo = "SELECT td_memberNo FROM tb_tarikdiri WHERE td_tarikdiriID = $applicationId";
$result = mysqli_query($con, $sqlGetMemberNo);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $memberNumber = $row['td_memberNo'];
}

// Function to send an email for setting password
function sendApprovalEmail($email, $name, $memberNo) {
    $subject = "Kelulusan Permohonan Berhenti Keanggotaan";
    $message = "
    <html>
    <body>
        <p>Encik/Puan {$name},</p>
        <p>Kami ingin memaklumkan bahawa permohonan anda untuk berhenti sebagai anggota Koperasi Kakitangan KADA telah diluluskan.</p>
        <p>Adalah menjadi penghormatan kami untuk bekerjasama dengan anda sepanjang keanggotaan anda. Kami menghargai sumbangan dan sokongan anda kepada koperasi ini. Semoga anda terus maju dan berjaya dalam apa jua bidang yang anda ceburi.</p>
        <p>Jika terdapat sebarang pertanyaan lanjut, sila hubungi pihak kami.</p>
        <p>Terima kasih.</p>
        <p>Yang ikhlas,</p>
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
if ($status == 3) {
    // Approval
    $sqlMem = "UPDATE tb_member
               SET m_status = '5'
               WHERE m_memberNo = $memberNumber";

    $sqlTD = "UPDATE tb_tarikdiri
              SET td_status = '3', td_approvalDate = '$currentDate', td_ulasan = '$reviewComments', td_adminID = '$uid'
              WHERE td_tarikdiriID = $applicationId";

    $sqlUser = "DELETE FROM tb_user
                WHERE u_id = '$memberNumber'";

    // Execute queries
    if (mysqli_query($con, $sqlMem) && mysqli_query($con, $sqlTD) && mysqli_query($con, $sqlUser)) {
        // Fetch member email and name (Email Sending Purpose)
        $sqlFetch = "SELECT m_email, m_name, m_memberNo FROM tb_member WHERE m_memberNo = $memberNumber";
        $result = mysqli_query($con, $sqlFetch);
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $email = $row['m_email'];
            $name = $row['m_name'];
            $memberNo = $row['m_memberNo'];

            if (!$email) {
                error_log("Email is empty or not found for member ID $memberNumber");
            }

            // Send the approval email
            if (sendApprovalEmail($email, $name, $memberNo)) {
                echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Kelulusan Berhenti',
                            text: 'Kelulusan permohonan berjaya diproses! E-mel telah dihantar kepada anggota.',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = 'berhenti_approval.php';
                        });
                      </script>";
            } else {
                error_log("Failed to send email to $email");
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menghantar E-mel',
                            text: 'Kelulusan permohonan berjaya diproses, tetapi gagal menghantar e-mel.',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = 'berhenti_approval.php';
                        });
                      </script>";
            }
        }
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Ralat Memproses',
                    text: 'Ralat memproses kelulusan permohonan: " . mysqli_error($con) . "',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.history.back();
                });
              </script>";
    }
} elseif ($status == 2 && $ulasan) {
    // Rejection
    $sqlReject = "UPDATE tb_tarikdiri
                  SET td_adminID = '$uid', td_status = '$status', td_approvalDate = '$currentDate', td_ulasan = '$ulasan'
                  WHERE td_tarikdiriID = $applicationId";

    if (mysqli_query($con, $sqlReject)) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Permohonan Ditolak',
                    text: 'Permohonan anggota telah berjaya ditolak.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'berhenti_approval.php';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Ralat Menolak Permohonan',
                    text: 'Ralat menolak permohonan anggota: " . mysqli_error($con) . "',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.history.back();
                });
              </script>";
    }
} else {
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Tindakan Tidak Sah',
                text: 'Tindakan tidak sah.',
                confirmButtonText: 'OK'
            }).then(() => {
                window.history.back();
            });
          </script>";
}

// Close Connection
mysqli_close($con);
?>
