<?php 
// Start session
//hi
session_start();

// Check if session variables are set
if (!isset($_SESSION['uid'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Check if the 'status' parameter is present in the URL
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    echo '<script>alert("Anda telah berjaya disimpan.");</script>';
}
  
//Redirect user to semakan_butiran page
header('Location:semakan_butiran.php?status=success');

?>