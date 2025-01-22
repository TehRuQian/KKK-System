<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../headermember.php';
include '../db_connect.php';
if ($_SESSION['u_type'] != 2) {
    header('Location: ../login.php');
    exit();
  }
  
$status = isset($_POST['status']) ? intval($_POST['status']) : 0;


$loanApplicationID = $_SESSION['loanApplicationID'];  
$sql = "UPDATE tb_loan SET l_status = ? WHERE l_loanApplicationID = ?";
$stmt = $con->prepare($sql);


$statusValue = 1; 
$stmt->bind_param("is", $statusValue, $loanApplicationID); 

if ($stmt->execute()) {
    echo "Status updated successfully.";
    header('Location: dashboard_pinjaman.php?status=success');
} else {
    echo "Error updating status: " . $stmt->error;
}

$stmt->close();
$con->close();

?>