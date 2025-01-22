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

    $sql = "SELECT m_name, m_ic, m_pfNo FROM tb_member WHERE m_memberNo = '$anggotaPenjamin1'";
    $result = mysqli_query($con, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        $response['penjamin1'] = $row; 
    } else {
        $response['penjamin1_error'] = 'No member found.'; 
    }
}


if (isset($_POST['anggotaPenjamin2'])) {
    $anggotaPenjamin2 = $_POST['anggotaPenjamin2'];

    
    $sql = "SELECT m_name, m_ic, m_pfNo FROM tb_member WHERE m_memberNo = '$anggotaPenjamin2'";
    $result = mysqli_query($con, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        $response['penjamin2'] = $row; 
    } else {
        $response['penjamin2_error'] = 'No member found.'; 
    }
}

$response['debug'] = [
    'post_data' => $_POST,
    'sql_error' => mysqli_error($con)
];

mysqli_close($con);
echo json_encode($response);
?>