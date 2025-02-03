<?php
include '../db_connect.php';
$message = $_POST['c_message'];
$user_id = $_POST['c_userid'];
$is_predefined = isset($_POST['is_predefined']) ? 1 : 0;
$stmt = $pdo->prepare("INSERT INTO tb_chat (user_id, c_msg, c_question, c_time) VALUES (?, ?, ?, NOW())");
$stmt->execute([$user_id, $message, $is_predefined]);
echo "Message Sent!";
?>
