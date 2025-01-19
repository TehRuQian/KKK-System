<?php
date_default_timezone_set('Asia/Kuala_Lumpur');  

// Connect to DB
include('db_connect.php');
if ($con) {
    include 'forgot_password.php';
} else {
    echo "Failed to connect to the database.";
}

if ($_POST) {
    $cleanPost = cleanPost($_POST);
    
    $userID = $cleanPost['fuid'];
    $email = $cleanPost['femail'];

    // Check database for matching ID and email
    $sql = "SELECT m_memberNo, m_email 
            FROM tb_member
            WHERE m_memberNo = ? AND m_email = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'is', $userID, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // If match found, generate a unique token
        $token = bin2hex(random_bytes(16)); // Generates a random 16-byte token
        $expiryTime = date("Y-m-d H:i:s", strtotime("+10 minutes", time()));
        $sql = "UPDATE tb_user SET reset_token = ?, token_expiry = ? WHERE u_id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 'ssi', $token, $expiryTime, $userID);
        mysqli_stmt_execute($stmt);

        // Create the reset password link with the token
        $resetLink = "http://127.0.0.1/KKK-System/reset_password.php?token=$token";

        //  send email
        $subject = "Reset Kata Laluan Anda";
        $message = "Klik pautan ini untuk menetapkan semula kata laluan anda: <a href=$resetLink>$resetLink</a>";

        if (send_email($email, $subject, $message)) {
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berjaya',
                    text: 'Pautan ubah kata laluan telah dihantar ke emel anda.'
                });
            </script>";

        } else {
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                });
            </script>";
        }
    } else {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Emel tidak didaftar',
            });
        </script>";
    }

    exit;
}
?>
