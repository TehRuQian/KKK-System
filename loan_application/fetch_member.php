<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    echo json_encode(['error' => 'User not logged in.']);
    exit();
}

// Connect to the database
include('dbconnect.php');

// Initialize response array
$response = [];

// Function to fetch member details based on member number
function fetch_member_details($anggota) {
    global $con;  // Database connection

    // SQL query to fetch member details
    $sql = "SELECT m_name, m_ic, m_pfNo FROM tb_member WHERE m_memberNo = ?";
    $stmt = mysqli_prepare($con, $sql);

    
    mysqli_stmt_bind_param($stmt, 'i', $anggota);
    mysqli_stmt_execute($stmt);

    // Fetch the result
    $result = mysqli_stmt_get_result($stmt);

    // Return the result
    if ($row = mysqli_fetch_assoc($result)) {
        return [
            'm_name' => htmlspecialchars($row['m_name']),
            'm_ic' => htmlspecialchars($row['m_ic']),
            'm_pfNo' => htmlspecialchars($row['m_pfNo'])
        ];
    } else {
        return null;  
    }

    
    mysqli_stmt_close($stmt);
}

// first Penjamin
if (isset($_POST['anggotaPenjamin1'])) {
    $anggotaPenjamin1 = $_POST['anggotaPenjamin1'];
    $penjamin1_details = fetch_member_details($anggotaPenjamin1);

    if ($penjamin1_details) {
        $response['penjamin1'] = $penjamin1_details;
    } else {
        $response['penjamin1_error'] = 'No member found with this No. Anggota for Penjamin 1.';
    }
}

// second Penjamin
if (isset($_POST['anggotaPenjamin2'])) {
    $anggotaPenjamin2 = $_POST['anggotaPenjamin2'];
    $penjamin2_details = fetch_member_details($anggotaPenjamin2);

    if ($penjamin2_details) {
        $response['penjamin2'] = $penjamin2_details;
    } else {
        $response['penjamin2_error'] = 'No member found with this No. Anggota for Penjamin 2.';
    }
}

// Close the database
mysqli_close($con);

// Return the response as JSON
echo json_encode($response);

?>
