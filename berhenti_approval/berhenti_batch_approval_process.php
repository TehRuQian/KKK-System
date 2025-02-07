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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_members']) && isset($_POST['action'])) {
    $selectedMembers = $_POST['selected_members'];
    $action = $_POST['action'];
    $uid = $_SESSION['u_id'];
    $currentDate = date('Y-m-d H:i:s');
    
    if ($action === 'approve') {
        foreach ($selectedMembers as $applicationId) {
            // Fetch member details
            $sqlGetMember = "SELECT td_memberNo, m_email, m_name FROM tb_tarikdiri 
                             JOIN tb_member ON tb_tarikdiri.td_memberNo = tb_member.m_memberNo
                             WHERE td_tarikdiriID = $applicationId";
            $result = mysqli_query($con, $sqlGetMember);
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $memberNumber = $row['td_memberNo'];
                $email = $row['m_email'];
                $name = $row['m_name'];

                // Update records
                $sqlMem = "UPDATE tb_member SET m_status = '5' WHERE m_memberNo = $memberNumber;";
                $sqlTD = "UPDATE tb_tarikdiri 
                          SET td_status = '3', td_approvalDate = '$currentDate', td_ulasan = 'Permohonan diluluskan', td_adminID = '$uid' 
                          WHERE td_tarikdiriID = $applicationId;";
                $sqlUser = "DELETE FROM tb_user WHERE u_id = '$memberNumber';";

                // Execute all queries
                if (mysqli_query($con, $sqlMem) && mysqli_query($con, $sqlTD) && mysqli_query($con, $sqlUser)) {
                    // Fetch member email and name for email notification
                    $sqlFetch = "SELECT m_email, m_name FROM tb_member WHERE m_memberNo = $memberNumber";
                    $result = mysqli_query($con, $sqlFetch);
                    $row = mysqli_fetch_assoc($result);
                
                    if ($row) {
                        $email = $row['m_email'];
                        $name = $row['m_name'];
                
                        if (!empty($email)) {
                            if (sendApprovalEmail($email, $name, $memberNumber)) {
                                $emailMessage = "E-mel telah dihantar kepada anggota.";
                            } else {
                                $emailMessage = "Kelulusan permohonan berjaya diproses, tetapi gagal menghantar e-mel.";
                                error_log("Gagal menghantar e-mel kepada $email");
                            }
                        } else {
                            $emailMessage = "Kelulusan permohonan berjaya diproses, tetapi tiada e-mel anggota yang tersedia.";
                        }
                    } else {
                        $emailMessage = "Kelulusan permohonan berjaya diproses, tetapi gagal mendapatkan maklumat e-mel anggota.";
                    }
                
                    echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Kelulusan Berhenti',
                                text: '$emailMessage',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = 'berhenti_approval.php';
                            });
                          </script>";
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
                
            }
        }
    } elseif ($action === 'reject' && isset($_POST['rejectionData'])) {
        $rejectionData = json_decode($_POST['rejectionData'], true);
        foreach ($rejectionData as $data) {
            $reason = mysqli_real_escape_string($con, $data['ulasan']);
            $sql = "UPDATE tb_tarikdiri 
                    SET td_status = 2, td_approvalDate = '$currentDate', td_ulasan = '$reason', td_adminID = '$uid' 
                    WHERE td_tarikdiriID = " . $data['id'];
            mysqli_query($con, $sql);
        }
    }
    header("Location: berhenti_approval.php?success=1");
    exit();
}

// Function to send an email for approval
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
               "From: no_reply@kkk.com\r\n";

    return mail($email, $subject, $message, $headers);
}
?>
