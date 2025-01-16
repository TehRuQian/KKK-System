<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../headermember.php';
include '../db_connect.php';
// // Check if session variables are set
// if (!isset($_SESSION['uid'])) {
//     header('Location: login.php'); // Redirect to login if not logged in
//     exit();
// }

header('Location: dashboard_pinjaman.php?status=success');
//close SQL
mysqli_close($con);
?>