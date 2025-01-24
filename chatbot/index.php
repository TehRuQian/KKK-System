<?php
include '../db_connect.php';

// Fetch all predefined questions from the database
$sql = "SELECT q_id, q_question FROM tb_inquiries";
$result = $con->query($sql);

// Prepare questions array
$questions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Support Chatbot</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="chatbot-container">
        <div id="chatbot" class="chatbot" style="height: 565px;">
            <div class="chatbot-header" style="height: 36px;">
                <h3>Sokongan Pelanggan</h3>
                <button id="minimize-btn">-</button>
            </div>
            <div class="chatbot-body">
                <div class="quick-questions" style="height: 200px;">
                    <h4>Soalan Lazim</h4>
                    <div id="predefined-questions-container">
                    <?php foreach ($questions as $question): ?>
                        <button class="predefined-question" data-id="<?= $question['q_id']; ?>">
                        <?= htmlspecialchars($question['q_question']); ?>
                        </button>
                    <?php endforeach; ?>
                    </div>
                </div>

                <div class="chat-messages" id="chat-messages" style="height: 200px;"></div>
                <div class="chat-input">
                    <input type="text" id="user-input" placeholder="Masukkan soalan anda...">
                    <button id="send-btn">Hantar</button>
                </div>
            </div>
        </div>
        <div class="chatbot-icon" id="chatbot-icon" style="cursor: pointer;">
            <img src="../img/chat.png" alt="" id="" style="cursor: pointer;" width="50">
        </div>
    </div>
    <script src="chatbot.js"></script>
</body>
</html>

<?php
$con->close();
?>
