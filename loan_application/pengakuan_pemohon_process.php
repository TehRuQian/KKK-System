<?php 
// Start session
session_start();

// Check if session variables are set
if (!isset($_SESSION['uid'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    echo "<script>
            alert('Form submitted successfully!');
            window.location.href = 'penjamin.php';
          </script>";

} else {

    header('Location: penjamin.php?status=success'); 
    exit();
}
?>