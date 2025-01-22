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
if (empty($_POST['lstatus']) || empty($_POST['lApplicationID'])) {
   echo "<script>
            alert('Invalid submission. Please try again.');
            window.history.back();
          </script>";
    exit;
}
 
// Validate loan status
$statusCheck = mysqli_query($con, "SELECT COUNT(*) AS count FROM tb_status WHERE s_sid = $loanStatus");
$statusRow = mysqli_fetch_assoc($statusCheck);

if ($statusRow['count'] == 0) {
    echo "<script>
            alert('Invalid loan status selected.');
            window.history.back();
          </script>";
    exit;
}

// SQL Update Operation


// Approval & Rejection
if ($loanStatus == 3) {
  // Approval
  $sql = "UPDATE tb_loan
          SET l_adminID = '$uid', l_status = '$loanStatus', l_approvalDate = '$currentDate'
          WHERE l_loanApplicationID = $lApplicationID";

  // Execute queries
  if (mysqli_query($con, $sql)) {
    echo "<script>
            alert('Kelulusan pinjaman berjaya diproses!');
            window.location.href = 'loan_approval.php';
          </script>";
      }
  else {
      echo "<script>
              alert('Ralat memproses kelulusan pinjaman: " . mysqli_error($con) . "');
              window.history.back();
            </script>";
  }
} elseif ($loanStatus == 2) {
  // Rejection
  $sqlReject = "UPDATE tb_loan
                SET l_adminID = '$uid', l_status = '$loanStatus', l_approvalDate = '$currentDate'
                WHERE l_loanApplicationID = $lApplicationID";

  if (mysqli_query($con, $sqlReject)) {
      echo "<script>
              alert('Applikasi pinjaman telah berjaya ditolak.');
              window.location.href = 'loan_approval.php';
            </script>";
  } else {
      echo "<script>
              alert('Ralat menolak applikasi pinjaman: " . mysqli_error($con) . "');
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