<?php

include 'headermain.php';
include('db_connect.php');

// Define the SweetAlert script (only once in the head)
$sweetAlertScript = '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

$alertMessage = ''; // Variable to store SweetAlert message
$redirectToLogin = false; // Flag for redirection

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

                    // Success message and redirection flag
                    $alertMessage = "Kata laluan berjaya diubah.";
                    $redirectToLogin = true;
                } else {
                    // Error message if passwords don't match
                    $alertMessage = "Kata laluan mesti sama.";
                }
            }
        } else {
            // Token has expired
            $alertMessage = "Token tidak sah atau telah tamat masa.";
        }
    } else {
        // Token not found in the database
        $alertMessage = "Token tidak sah atau telah tamat masa.";
    }
} else {
    echo "<p>No token provided.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ubah Kata Laluan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <?php if ($alertMessage): ?>
        <?= $sweetAlertScript; ?> 
    <?php endif; ?>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-half {
            height: 60%;
            background: url('img/sky.jpg') center top no-repeat;
            background-size: cover;
        }

        .bottom-half {
            height: 40%;
            background: white;
        }

        .login-container {
            height: 350px;
            width: 430px;
            margin: auto;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -30%);
            background: rgba(255, 255, 255, 0.8);
            border-radius: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .container {
            width: 300px;
            height: 250px;
            margin: 0 auto;
        }

        img {
            display: block;
            margin: auto;
        }

        h1 {
            font-family: 'Inter'; font-size: 35px;
            font-weight: bold;
            color: black;
        }

        h6 {
            font-family: 'Inter'; font-size: 12px;
            color: black;
        }

        .custom-width {
            width: 200px;
        }

        .center-button {
            margin: 0;
            position: absolute;
            left: 50%;
            transform: translate(-50%, -4%);
        }

        a:link {
            text-decoration: underline;
        }

        a:hover {
            text-decoration: underline;
        }

        a:active {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="top-half">
        <br>
        <img src="img/kkk_logo_cropped.png" style="width:10%">
        <br><br>
        <h1 class="text-center">Ubah Kata Laluan</h1>
    </div>
    <div class="bottom-half">
        <div class="login-container">
            <div class="container">
                <br><br>
                <h5 class="text-center">Masukkan Kata Laluan Baru</h5>

                <?php if (isset($user)) : ?>
                    <form method="POST" action="reset_password.php">
                        <fieldset>
                            <div>        
                                <input type="hidden" name="token" value="<?php echo $token; ?>">

                                <div class="form-floating mb-3 mt-2">
                                    <input type="password" name="new_password" class="form-control" id="floatingInput" placeholder="" required>
                                    <label for="floatingInput" class="text-muted d-flex justify-content-center">Kata Laluan Baharu</label>
                                </div>
                                <div class="form-floating">
                                    <input type="password" name="confirm_password" class="form-control" id="floatingEmail" placeholder="" required>
                                    <label for="floatingEmail" class="text-muted d-flex justify-content-center">Sahkan Kata Laluan</label>
                                </div>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary custom-width center-button">Ubah</button>
                            <br><br><br>
                        </fieldset>
                    </form>
                <?php else : ?>
                    <p class="text-center">Token tidak sah atau telah tamat masa.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($alertMessage): ?>
        <!-- Only echo SweetAlert if there's a message -->
        <script>
            Swal.fire({
                icon: '<?php echo $redirectToLogin ? "success" : "error"; ?>',
                title: '<?php echo $alertMessage; ?>',
            }).then(() => {
                <?php if ($redirectToLogin): ?>
                    window.location.href = 'login.php'; // Redirect after success
                <?php endif; ?>
            });
        </script>
    <?php endif; ?>
</body>
</html>
