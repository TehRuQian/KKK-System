<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../header_admin.php';
include '../db_connect.php';

// Validate form data
if (empty($_POST['loanStatus']) || empty($_POST['lApplicationID'])) {
    echo "<script>
            alert('Invalid submission. Please try again.');
            window.history.back();
          </script>";
    exit;
}

// Retrieve data from form
$uid = $_SESSION['u_id'];
$loanStatus = intval($_POST['loanStatus']);
$lApplicationID = intval($_POST['lApplicationID']);
$currentDate = date('Y-m-d H:i:s');

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
$sql1 = "UPDATE tb_loan
        SET l_adminID = '$uid', l_status = '$loanStatus', l_approvalDate = '$currentDate'
        WHERE l_loanApplicationID = $lApplicationID";

// Execute SQL
if (mysqli_query($con, $sql)) {
    echo "<script>
            alert('Loan approval processed successfully!');
            window.location.href = 'loan_approval.php';
          </script>";
} else {
    echo "<script>
            alert('Error processing loan approval: " . mysqli_error($con) . "');
            window.history.back();
          </script>";
}

// Close Connection
mysqli_close($con);
?>
