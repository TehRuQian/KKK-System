<?php 
    session_start();
    if(isset($_SESSION['unique_id'])){
        include_once "config.php";
        $outgoing_id = $_SESSION['unique_id'];
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $message = mysqli_real_escape_string($conn, $_POST['message']);
        if(!empty($message)){
            // Check if the recipient is an admin
            $check_admin_sql = "SELECT u_type FROM tb_user WHERE u_id = {$incoming_id}";
            $check_admin_query = mysqli_query($conn, $check_admin_sql);
            if(mysqli_num_rows($check_admin_query) > 0){
                $admin_type = mysqli_fetch_assoc($check_admin_query)['u_type'];
                if($admin_type == 1){
                    $sql = mysqli_query($conn, "INSERT INTO tb_chat (c_userid, c_incoming, c_outgoing, c_msg)
                                                VALUES ({$outgoing_id}, {$incoming_id}, {$outgoing_id}, '{$message}')") or die();
                }
            }
        }
    }else{
        header("location: ../login.php");
    }
?>