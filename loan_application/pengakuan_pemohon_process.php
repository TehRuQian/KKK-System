<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../headermember.php';
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    echo "<script>
            alert('Form submitted successfully!');
            window.location.href = 'penjamin.php';
          </script>";

} else {

    header('Location: penjamin.php?status=success'); 
    exit();
}
?>