<?php
session_start();
// Start output buffering
ob_start(); 
// Connect to DB
include('db_connect.php');
if ($con) {
    include 'login.php';
} else {
    echo "Failed to connect to the database.";
}

//error array
$errors = [];

// Sanitise Data
if ($_POST){
    $cleanPost = cleanPost($_POST);

    $funame = $cleanPost['funame'];
    $fpwd = $cleanPost['fpwd'];  
    if(empty($errors)){
        $sql = "SELECT * FROM tb_user WHERE u_id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $funame);  
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        $matches = false;
       

        if($user){
            $matches = password_verify($fpwd, $user['u_pwd']);

            if($matches){
                // Successful login - Set session and redirect
                $_SESSION['u_id'] = $user['u_id'];  // Set the session variable for the user
                $_SESSION['funame'] = $user['u_id'];  // Store username in session
                $_SESSION['u_type'] = $user['u_type'];

                // Redirect based on user type
                if ($user['u_type'] == 1) { // Admin type
                    header('Location: admin_main/admin.php');
                    exit;
                } else if ($user['u_type'] == 2) { // Member type
                    header('Location: member_main/member.php');
                    exit;
                }
            }

            else {
                echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
                      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
                      <script>
                          Swal.fire({
                              icon: "error",
                              title: "Log Masuk Gagal.",
                              text: "Pengguna ID atau Kata Laluan Salah.",
                              footer: "<a href=\'forgot_password.php\'>Lupa Kata Laluan</a>"
                          });
                      </script>';
                      exit; 
            }
        }
        else echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
        <script>
            Swal.fire({
                icon: "error",
                title: "Log Masuk Gagal.",
                text: "Pengguna tidak wujud.",
            });
        </script>';
        exit; 
        
    }
}


mysqli_close($con);
ob_end_flush(); 
?>

