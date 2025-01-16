<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../headermember.php';
include '../db_connect.php';

header('Location: pinjaman.php');
//close SQL
mysqli_close($con);
?>