<?php
if(!session_id())
{
    session_start();
}

if(isset($_SESSION['u_id']) != session_id())
{
    header('Location: ../login.php');
}

if (!isset($_SESSION['u_type'])) {
    header('Location: ../login.php');
    exit();
}
?>
