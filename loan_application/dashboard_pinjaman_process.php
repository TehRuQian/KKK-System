<?php
include('../kkksession.php');
if(!session_id())
{
  session_start();
}
if ($_SESSION['u_type'] != 2) {
  header('Location: ../login.php');
  exit();
}

if(isset($_SESSION['u_id']) != session_id())
{
  header('Location:../login.php'); 
}

include '../headermember.php';
include '../db_connect.php';
$memberNo = $_SESSION['funame'];

header('Location: a_pinjaman.php');
//close SQL
mysqli_close($con);
?>