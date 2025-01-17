<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    include('../kkksession.php');
    include('../db_connect.php');

    if (!$con) {
        throw new Exception("Database connection failed: " . mysqli_connect_error());
    }

   
    if(isset($_POST['anggotaPenjamin1'])) {
        $memberNo = mysqli_real_escape_string($con, $_POST['anggotaPenjamin1']);
        $sql = "SELECT m_memberNo, m_name, m_ic, m_pfNo 
                FROM tb_member 
                WHERE m_memberNo = ?";
                
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $memberNo);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if(mysqli_num_rows($result) > 0) {
            $member = mysqli_fetch_assoc($result);
            echo json_encode([
                'status' => 'success',
                'penjamin1' => [
                    'm_name' => $member['m_name'],
                    'm_ic' => $member['m_ic'],
                    'm_pfNo' => $member['m_pfNo']
                ]
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Tiada maklumat anggota dijumpai'
            ]);
        }
        mysqli_stmt_close($stmt);
    }
    
    
    if(isset($_POST['anggotaPenjamin2'])) {
        $memberNo = mysqli_real_escape_string($con, $_POST['anggotaPenjamin2']);
        $sql = "SELECT m_memberNo, m_name, m_ic, m_pfNo 
                FROM tb_member 
                WHERE m_memberNo = ?";
                
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $memberNo);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if(mysqli_num_rows($result) > 0) {
            $member = mysqli_fetch_assoc($result);
            echo json_encode([
                'status' => 'success',
                'penjamin2' => [
                    'm_name' => $member['m_name'],
                    'm_ic' => $member['m_ic'],
                    'm_pfNo' => $member['m_pfNo']
                ]
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Tiada maklumat anggota dijumpai'
            ]);
        }
        mysqli_stmt_close($stmt);
    }

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($con)) {
        mysqli_close($con);
    }
}
?>