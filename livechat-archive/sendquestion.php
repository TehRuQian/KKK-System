<?php
include 'db.php';
$user_id = $_POST['user_id'];
$message = $_POST['message'];
$stmt = $pdo->prepare("INSERT INTO chats (user_id, message, is_predefined, created_at) VALUES (?, ?, 0, NOW())");
$stmt->execute([$user_id, $message]);
echo json_encode(['status' => 'success']);
?>
