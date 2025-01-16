<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../headermember.php';
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    echo "<script>
            alert('Anda telah berjaya disimpan.');
            window.location.href = 'd_penjamin.php';
          </script>";

} else {

    header('Location: d_penjamin.php?status=success'); 
    exit();
}
?>