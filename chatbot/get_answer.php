<?php
include '../db_connect.php';

$q_id = isset($_GET['q_id']) ? intval($_GET['q_id']) : 0;

if ($q_id > 0) {
    try {
        $stmt = $con->prepare("SELECT q_answer FROM tb_inquiries WHERE q_id = ?");
        $stmt->bind_param("i", $q_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode(['answer' => $row['q_answer']]);
        } else {
            echo json_encode(['error' => 'No answer found.']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'Database query failed: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid question ID.']);
}

$con->close();
?>