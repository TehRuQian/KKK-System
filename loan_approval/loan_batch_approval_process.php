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

$uid = $_SESSION['u_id'];
$loanStatus = NULL;
$selectedLoans = isset($_POST['selected_loans']) ? $_POST['selected_loans'] : [];
$currentDate = date('Y-m-d H:i:s');

if (!isset($_POST['action'])) {
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Tiada tindakan dipilih',
                text: 'Sila pilih untuk meluluskan atau menolak pinjaman.'
            }).then(() => {
                window.history.back();
            });
          </script>";
    exit;
}

if ($_POST['action'] == 'approve') {
    $loanStatus = 3; // Approved
    $actionText = 'meluluskan';
} elseif ($_POST['action'] == 'reject') {
    $loanStatus = 2; // Rejected
    $actionText = 'menolak'; 
} else {
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Tindakan tidak sah',
                text: 'Tindakan yang dipilih tidak sah. Sila cuba lagi.'
            }).then(() => {
                window.history.back();
            });
          </script>";
    exit;
}

if (empty($selectedLoans) || !is_array($selectedLoans)) {
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Ralat',
                text: 'Sila pilih sekurang-kurangnya satu permohonan pinjaman.'
            }).then(() => {
                window.history.back();
            });
          </script>";
    exit;
}

$errors = [];

foreach ($selectedLoans as $lApplicationID) {
    $lApplicationID = intval($lApplicationID); 
    
    $sql = "UPDATE tb_loan 
            SET l_adminID = '$uid', 
                l_status = '$loanStatus', 
                l_approvalDate = '$currentDate' 
            WHERE l_loanApplicationID = $lApplicationID";
    
    if (!mysqli_query($con, $sql)) {
        $errors[] = "Ralat memproses permohonan pinjaman ID: $lApplicationID - " . mysqli_error($con);
    }
}

if (empty($errors)) {
    if ($_POST['action'] == 'approve') {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Kelulusan Berjaya',
                    text: 'Pinjaman berjaya diluluskan!'
                }).then(() => {
                    window.location.href = 'loan_approval.php';
                });
              </script>";
    } elseif ($_POST['action'] == 'reject') {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Penolakan Berjaya',
                    text: 'Pinjaman berjaya ditolak!'
                }).then(() => {
                    window.location.href = 'loan_approval.php';
                });
              </script>";
    }
} else {
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Ralat',
                html: '<ul><li>" . implode("</li><li>", $errors) . "</li></ul>'
            }).then(() => {
                window.history.back();
            });
          </script>";
}
?>
