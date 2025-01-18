<?php
session_start();
require_once('../db_connect.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

error_log('Received request: ' . file_get_contents('php://input'));


$data = json_decode(file_get_contents('php://input'), true);


if (!isset($data['u_id']) || !$data['u_id']) {
    echo json_encode(['success' => false, 'message' => 'ID Admin diperlukan']);
    exit;
}


try {
    
    $sql = "INSERT INTO tb_reportretrievallog (r_retrievalDate, r_month, r_year, r_adminID) 
            VALUES (CURDATE(), ?, ?, ?)";

     
    error_log('SQL Query: ' . $sql);
    error_log('Parameters: month=' . ($data['month'] ?? 'null') . 
                ', year=' . ($data['year'] ?? 'null') . 
                ', adminID=' . $data['u_id']);
    
    
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iii", 
            $data['month'],
            $data['year'],
            $data['u_id']
        );
        
        if (mysqli_stmt_execute($stmt)) {
            error_log('Successfully inserted record');
            echo json_encode([
                'success' => true,
                'message' => 'Log berjaya disimpan'
            ]);
        } else {
            throw new Exception('Execute error: ' . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);
    } else {
        throw new Exception('Prepare error: ' . mysqli_error($con));
    }
    
} catch (Exception $e) {
    error_log("Error saving report log: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Ralat semasa menyimpan log: ' . $e->getMessage()
    ]);
}

mysqli_close($con);
?>