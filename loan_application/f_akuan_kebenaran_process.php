
<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../headermember.php';
include '../db_connect.php';


// Check if the 'status' parameter is present in the URL
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    echo '<script>alert("Maklumat anda telah berjaya disimpan!");</script>';
}
  
//Redirect user to semakan_butiran page
header('Location:semakan_butiran.php?status=success');

?>