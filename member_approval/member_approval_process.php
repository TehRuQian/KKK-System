<?php

include('../kkksession.php');
if(!session_id())
{
  session_start();
}

include '../header_admin.php';
include '../db_connect.php';

// Retrieve data from form
$uid = $_SESSION['u_id'];
$mstatus = $_POST['mstatus'];
$mApplicationID = $_POST['mApplicationID'];
$currentDate = date('Y-m-d');

// SQK Insert Operation
$sql = "UPDATE tb_member
        SET m_adminID = '$uid', m_status='$mstatus', m_approvalDate='$currentDate'
        WHERE m_memberApplicationID = $mApplicationID" ;

// Execute SQL
mysqli_query($con, $sql);

// Close Connection
mysqli_close($con);

// Confirmation Registration Successful or Fail (your task in individual project)

// Redirect User to View Member Approval List Page
header('Location: member_approval.php')
?>