<?php 
// Connect to DB
include('dbconnect.php');

// Start session
session_start();

// Check if session variables are set
if (!isset($_SESSION['uid'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

header('Location: pinjaman.php');
//close SQL
mysqli_close($con);
?>