<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}
if ($_SESSION['u_type'] != 1) {
    header('Location: ../login.php');
    exit();
}

include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selected_members']) && isset($_POST['action'])) {
    $selectedMembers = $_POST['selected_members'];
    $action = $_POST['action'];
    $uid = $_SESSION['u_id'];
    $currentDate = date('Y-m-d H:i:s');

    if ($action === 'approve') {
        foreach ($selectedMembers as $applicationId) {
            $sql = "UPDATE tb_tarikdiri SET td_status = 3, td_approvalDate = '$currentDate', td_ulasan = 'Permohonan diluluskan', td_adminID = '$uid' WHERE td_tarikdiriID = $applicationId";
            mysqli_query($con, $sql);
        }
    } elseif ($action === 'reject' && isset($_POST['rejectionData'])) {
        $rejectionData = json_decode($_POST['rejectionData'], true);
        foreach ($rejectionData as $data) {
            $reason = mysqli_real_escape_string($con, $data['ulasan']);
            $sql = "UPDATE tb_tarikdiri SET td_status = 2, td_approvalDate = '$currentDate', td_ulasan = '$reason', td_adminID = '$uid' WHERE td_tarikdiriID = " . $data['id'];
            mysqli_query($con, $sql);
        }
    }

    // Redirect with a success query parameter
    header("Location: berhenti_approval.php?success=1");
    exit();
}
?>
