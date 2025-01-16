<?php
session_start();
include 'headermain.php';
include 'login_function.php';

// Connect to DB
include('db_connect.php');

// Retrieve data from form
$funame = $_POST['funame'];
$fpwd = $_POST['fpwd'];

// SQL Retrieve Operation to get user data from DB (login)
$sql = "SELECT * FROM tb_user WHERE u_id= '$funame' AND u_pwd= '$fpwd'";

// Execute SQL
$result = mysqli_query($con, $sql);
// Retrieve data
$row = mysqli_fetch_array($result);
// Count result to check
$count = mysqli_num_rows($result);

// Rule-based AI login
if ($count == 1) { // Check if user exists
    // Set session
    $_SESSION['u_id'] = session_id();
    $_SESSION['funame'] = $funame;
    if ($row['u_type'] == 1) { // Check user type
        // Redirect to admin page
        header('Location: admin.php');
        exit;
    }
    if ($row['u_type'] == 2) { // Check user type
        // Redirect to Member page
        header('Location: member.php');
        exit;
    }
} else {
    // Login failed: Display SweetAlert
    echo "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script>";
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            text: 'Incorrect username or password. Would you like to reset your password?',
            footer: '<a href=\"javascript:void(0)\" onclick=\"pwResetEmail()\">Reset Password</a>'
        });
        
    </script>";
}

// Close connection
mysqli_close($con);
?>
