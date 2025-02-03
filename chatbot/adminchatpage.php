<?php
session_start();
if (!isset($_SESSION['unique_id'])) {
    header("location: ../login.php");
    exit();
}

include_once "config.php";

$current_id = $_SESSION['unique_id'];
$admin_type = 1; // Assuming the logged-in user is an admin

// Fetch all messages from members
$sql = "SELECT * FROM tb_chat 
        LEFT JOIN tb_user ON tb_user.u_id = tb_chat.c_userid
        WHERE tb_user.u_type = 2 AND tb_chat.c_incoming = {$current_id}
        ORDER BY c_id DESC";
$result = mysqli_query($conn, $sql);

$messages = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Chat</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <h3>Messages from Members</h3>
        </div>
        <div class="chat-box">
            <?php foreach ($messages as $message): ?>
                <div class="chat incoming">
                    <img src="php/images/<?php echo $message['u_img']; ?>" alt="">
                    <div class="details">
                        <p><?php echo htmlspecialchars($message['c_msg']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="chat-input">
            <input type="hidden" id="incoming_id" value="<?php echo $current_id; ?>">
            <input type="text" id="user-input" placeholder="Type your reply...">
            <button id="send-btn">Send</button>
        </div>
    </div>
    <script src="adminchat.js"></script>
</body>
</html>