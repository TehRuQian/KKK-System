<?php
session_start();
// Connect to DB
include('db_connect.php');
if ($con) {
    include 'reset_password.php';
} else {
    echo "Failed to connect to the database.";
}

// Check if the token is present in the URL or via form submission
if (isset($_GET['token']) || isset($_POST['token'])) {
    // Use the token from the URL or POST
    $token = isset($_POST['token']) ? $_POST['token'] : $_GET['token'];

    // Check if the token exists in the database and is not expired
    $sql = "SELECT * FROM tb_user WHERE reset_token = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        // Token found, check if it has expired
        $currentTime = new DateTime();
        $expiryTime = new DateTime($user['token_expiry']);

        // Compare the current time with the expiry time
        if ($currentTime < $expiryTime) {
            // Token is valid and not expired, display the reset password form
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Get the new password and confirmation password
                $newPassword = $_POST['new_password'];
                $confirmPassword = $_POST['confirm_password'];

                if ($newPassword === $confirmPassword) {
                    // Hash the new password
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                    // Update the password in the database and clear the reset token
                    $sql = "UPDATE tb_user SET u_pwd = ?, reset_token = NULL, token_expiry = NULL WHERE reset_token = ?";
                    $stmt = mysqli_prepare($con, $sql);
                    mysqli_stmt_bind_param($stmt, 'ss', $hashedPassword, $token);
                    mysqli_stmt_execute($stmt);

                    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Kata laluan berjaya diubah.',
                        });
                    </script>";
                } else {
                    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Kata laluan mesti sama.',
                        });
                    </script>";
                }
            }
        } else {
            // Token has expired
            echo "<p>Token tidak sah atau telah tamat masa.</p>";
        }
    } else {
        // Token not found in the database
        echo "<p>Token tidak sah atau telah tamat masa.</p>";
    }
} else {
    echo "<p>No token provided.</p>";
}
?>