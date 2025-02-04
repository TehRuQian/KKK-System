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
            $sqlGetMember = "SELECT ma_memberNo, m_email, m_name FROM tb_member_approval 
                             JOIN tb_member ON tb_member_approval.ma_memberNo = tb_member.m_memberNo
                             WHERE ma_id = $applicationId";
            $result = mysqli_query($con, $sqlGetMember);
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $memberNumber = $row['ma_memberNo'];
                $email = $row['m_email'];
                $name = $row['m_name'];

                // Update records
                $sqlMem = "UPDATE tb_member SET m_status = '5' WHERE m_memberNo = $memberNumber;";
                $sqlMA = "UPDATE tb_member_approval 
                          SET ma_status = '3', ma_approvalDate = '$currentDate', ma_ulasan = 'Permohonan diluluskan', ma_adminID = '$uid' 
                          WHERE ma_id = $applicationId;";
                $sqlUser = "DELETE FROM tb_user WHERE u_id = '$memberNumber';";

                // Execute all queries
                if (mysqli_query($con, $sqlMem) && mysqli_query($con, $sqlMA) && mysqli_query($con, $sqlUser)) {
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
                                title: 'Kelulusan Keanggotaan',
                                text: '$emailMessage',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = 'member_approval.php';
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
            $sql = "UPDATE tb_member_approval 
                    SET ma_status = 2, ma_approvalDate = '$currentDate', ma_ulasan = '$reason', ma_adminID = '$uid' 
                    WHERE ma_id = " . $data['id'];
            mysqli_query($con, $sql);
        }
    }
    header("Location: member_approval.php?success=1");
    exit();
}

// Function to send an email for approval
function sendApprovalEmail($email, $name, $memberNo) {
    $subject = "Kelulusan Permohonan Keanggotaan Koperasi Kakitangan KADA";
    $message = "
    <html>
    <body>
        <p>Encik/Puan {$name},</p>
        <p>Kami ingin memaklumkan bahawa permohonan anda untuk menjadi anggota Koperasi Kakitangan KADA telah diluluskan.</p>
        <p>Berikut adalah maklumat penting berkaitan keanggotaan anda:</p>
        <ul>
            <li><strong>No. Keanggotaan:</strong> {$memberNo}</li>
            <li><strong>Status:</strong> Diluluskan</li>
        </ul>
        <p>Anda kini merupakan ahli aktif Koperasi Kakitangan KADA. Kami menantikan kerjasama yang baik dengan anda.</p>
        <p>Jika terdapat sebarang pertanyaan atau jika anda memerlukan bantuan lanjut, sila hubungi kami melalui emel ini atau telefon.</p>
        <p>Terima kasih kerana memilih untuk menjadi sebahagian daripada komuniti kami.</p>
        <p>Yang ikhlas,</p>
        <p>Tech-Hi-Five</p>
        <p><small>Ini adalah emel automatik. Sila tidak membalas emel ini.</small></p>
    </body>
    </html>";

    $headers = "MIME-Version: 1.0\r\n" . 
               "Content-Type: text/html; charset=UTF-8\r\n" . 
               "From: no_reply@kkk.com\r\n";

    return mail($email, $subject, $message, $headers);
}
?>
