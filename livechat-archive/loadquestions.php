<?php
include '../db_connect.php';
$stmt = $pdo->query("SELECT * FROM predefined_questions");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
