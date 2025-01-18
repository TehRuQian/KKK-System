<?php
include('../kkksession.php');
if(!session_id())
{
  session_start();
}

if(isset($_SESSION['u_id']) != session_id())
{
  header('Location:../login.php'); 
  exit();
}

include '../db_connect.php';
$adminNo = $_SESSION['u_id'];

$month = isset($_POST['month']) ? $_POST['month'] : null;
$year = isset($_POST['year']) ? $_POST['year'] : null;

error_log("Received parameters - Month: " . print_r($month, true) . ", Year: " . print_r($year, true) . ", AdminNo: " . print_r($adminNo, true));

if ($month === null || $year === null) {
    error_log("Invalid parameters received");
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
    header('Location: _dashboardLaporan.php?status=error');
    exit();
}

try {
    // SQL Insert Operation
    $sql = "INSERT INTO tb_reportretrievallog (r_retrievalDate, r_month, r_year, r_adminID) 
            VALUES (CURRENT_TIMESTAMP(), ?, ?, ?)";
    
    // 使用预处理语句
    $stmt = $con->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $con->error);
    }
    
    $stmt->bind_param("sss", $month, $year, $adminNo);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success']);
    } else {
        throw new Exception("No rows inserted");
    }
    
    $stmt->close();
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    $con->close();
}
?>