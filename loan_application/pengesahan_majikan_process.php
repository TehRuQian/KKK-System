<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../headermember.php';
include '../db_connect.php';

//Redirect user to login page
header('Location:akuan_kebenaran.php?status=success');

?>