<?php 
    session_start();
    if(isset($_SESSION['unique_id'])){
        include_once "config.php";
        $current_id = $_SESSION['unique_id'];
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $output = "";
        $sql = "SELECT * FROM tb_chat 
                LEFT JOIN tb_user ON tb_user.u_id = tb_chat.c_userid
                WHERE (c_userid = {$current_id} AND c_incoming = {$incoming_id})
                OR (c_userid = {$incoming_id} AND c_outgoing = {$current_id} AND tb_user.u_type = 1) 
                ORDER BY c_id";
        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) > 0){
            while($row = mysqli_fetch_assoc($query)){
                if($row['c_userid'] === $current_id){
                    $output .= '<div class="chat outgoing">
                                <div class="details">
                                    <p>'. $row['c_msg'] .'</p>
                                </div>
                                </div>';
                }else{
                    $output .= '<div class="chat incoming">
                                <img src="php/images/'.$row['u_img'].'" alt="">
                                <div class="details">
                                    <p>'. $row['c_msg'] .'</p>
                                </div>
                                </div>';
                }
            }
        }else{
            $output .= '<div class="text">No messages are available. Once you send a message, they will appear here.</div>';
        }
        echo $output;
    }else{
        header("location: ../login.php");
    }
?>