<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../db_connect.php';

header('Content-Type: application/json');

if (!$con) {
    die(json_encode(['error' => 'Database connection failed']));
}


function fetch_member_details($anggota) {
    global $con;

    $sql = "SELECT m_name, m_ic, m_pfNo FROM tb_member WHERE m_memberNo = ?";
    $stmt = mysqli_prepare($con, $sql);

    if (!$stmt) {
        return null;
    }

    mysqli_stmt_bind_param($stmt, 's', $anggota);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        return [
            'm_name' => $row['m_name'],
            'm_ic' => $row['m_ic'],
            'm_pfNo' => $row['m_pfNo']
        ];
    }

    mysqli_stmt_close($stmt);
    return null;
}

$response = [];


if (isset($_POST['anggotaPenjamin1'])) {
    $anggotaPenjamin1 = $_POST['anggotaPenjamin1'];
    $penjamin1_details = fetch_member_details($anggotaPenjamin1);

    if ($penjamin1_details) {
        $response['penjamin1'] = $penjamin1_details;
    } else {
        $response['penjamin1_error'] = 'Tiada ahli ditemui dengan No. Anggota ini untuk Penjamin 1. Sila masukkan semula.';
    }
}


if (isset($_POST['anggotaPenjamin2'])) {
    $anggotaPenjamin2 = $_POST['anggotaPenjamin2'];
    $penjamin2_details = fetch_member_details($anggotaPenjamin2);

    if ($penjamin2_details) {
        $response['penjamin2'] = $penjamin2_details;
    } else {
        $response['penjamin2_error'] = 'Tiada ahli ditemui dengan No. Anggota ini untuk Penjamin 2. Sila masukkan semula.';
    }
}

$response['debug'] = [
    'post_data' => $_POST,
    'sql_error' => mysqli_error($con)
];

mysqli_close($con);
echo json_encode($response);
?>