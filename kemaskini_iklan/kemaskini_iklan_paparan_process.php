<?php
include '../db_connect.php';

include('../kkksession.php');
  if (!session_id()) {
      session_start();
  }
  if ($_SESSION['u_type'] != 1) {
    header('Location: ../login.php');
    exit();
  }  

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['bannerID'])) {
    $bannerID = $_POST['bannerID'];
    $bannerID = intval($bannerID); 

    $sql = "SELECT b_banner FROM tb_banner
            WHERE b_bannerID = $bannerID;";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $fileName = $row['b_banner'];
    $bannerPath = "../img/iklan/" . $fileName;
    if(file_exists($bannerPath)){
        unlink($bannerPath);
    }

    $sql = "DELETE FROM tb_banner 
            WHERE b_bannerID = $bannerID;";
    if(mysqli_query($con, $sql)){
        echo json_encode(['success' => true]);
    }
    else{
        echo json_encode(['success' => false, 'error' => mysqli_error($con)]);
    }

    mysqli_close($con);
    exit;
}

// Update Status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_status' && isset($_POST['bannerID']) && isset($_POST['status'])) {
    $bannerID = $_POST['bannerID'];
    $status = $_POST['status'];

    $sql = "UPDATE tb_banner SET b_status = $status WHERE b_bannerID = $bannerID";
    if (mysqli_query($con, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($con)]);
    }
    mysqli_close($con);
    exit;
}

// Check active banner
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'check_active_banners') {
    $sql = "SELECT COUNT(*) FROM tb_banner WHERE b_status = 1";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $row = mysqli_fetch_row($result);
        if ($row && isset($row[0])) {
            $count = $row[0];
        } else {
            $count = 0;
        }
    } else {
        $count = 0;
    }

    echo json_encode(['active_banners_count' => $count]);
    exit;
}

?>