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
$loanStatus = $_POST['lstatus'];
$lApplicationID = $_POST['lApplicationID'];
$currentDate = date('Y-m-d H:i:s');

// Validate form data
if (empty($loanStatus) || empty($lApplicationID)) {
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Ralat',
                text: 'Invalid submission. Please try again.'
            }).then(() => {
                window.history.back();
            });
          </script>";
    exit;
}

// Validate loan status
$statusCheck = mysqli_query($con, "SELECT COUNT(*) AS count FROM tb_status WHERE s_sid = $loanStatus");
$statusRow = mysqli_fetch_assoc($statusCheck);

if ($statusRow['count'] == 0) {
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Ralat',
                text: 'Invalid loan status selected.'
            }).then(() => {
                window.history.back();
            });
          </script>";
    exit;
}

// SQL Update Operation
if ($loanStatus == 3) {
    // Approval
    $sql = "UPDATE tb_loan
            SET l_adminID = '$uid', l_status = '$loanStatus', l_approvalDate = '$currentDate'
            WHERE l_loanApplicationID = $lApplicationID";

    if (mysqli_query($con, $sql)) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berjaya',
                    text: 'Kelulusan pinjaman berjaya diproses!'
                }).then(() => {
                    window.location.href = 'loan_approval.php';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Ralat',
                    text: 'Ralat memproses kelulusan pinjaman: " . mysqli_error($con) . "'
                }).then(() => {
                    window.history.back();
                });
              </script>";
    }
} elseif ($loanStatus == 2) {
    // Rejection
    $sqlReject = "UPDATE tb_loan
                  SET l_adminID = '$uid', l_status = '$loanStatus', l_approvalDate = '$currentDate'
                  WHERE l_loanApplicationID = $lApplicationID";

    if (mysqli_query($con, $sqlReject)) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berjaya',
                    text: 'Applikasi pinjaman telah berjaya ditolak.'
                }).then(() => {
                    window.location.href = 'loan_approval.php';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Ralat',
                    text: 'Ralat menolak applikasi pinjaman: " . mysqli_error($con) . "'
                }).then(() => {
                    window.history.back();
                });
              </script>";
    }
} else {
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Ralat',
                text: 'Tindakan tidak sah.'
            }).then(() => {
                window.history.back();
            });
          </script>";
}
?>
