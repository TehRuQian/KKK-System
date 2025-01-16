<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../headermember.php';
include '../db_connect.php';

header('Location: dashboard_pinjaman.php?status=success');
//close SQL
mysqli_close($con);
?>