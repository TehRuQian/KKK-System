<?php 
// Start session
session_start();

// Check if session variables are set
if (!isset($_SESSION['uid'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

//Redirect user to login page
header('Location:akuan_kebenaran.php?status=success');

?>