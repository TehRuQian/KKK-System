<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../headermember.php';
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    echo "<script>
            Swal.fire({
                title: 'Berjaya!',
                text: 'Maklumat anda telah berjaya disimpan!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'd_penjamin.php?status=success';
            });
          </script>";

} else {

    header('Location: c_pengakuan_pemohon.php'); 
    exit();
}
?>