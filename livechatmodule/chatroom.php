<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Chatroom</title>
    <style>
        #messages {
            width: 100%;
            height: 300px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            overflow-y: scroll;
        }
        input[type="text"] {
            width: 80%;
            padding: 5px;
        }
        input[type="button"] {
            padding: 5px 10px;
        }
    </style>
</head>
<body>

<h2>Simple Chatroom</h2>

<div id="messages"></div>
<input type="text" id="messageInput" placeholder="Type a message..." />
<input type="button" value="Send" onclick="sendMessage()" />

<script>
    var conn = new WebSocket('ws://localhost:8080');
    var messagesDiv = document.getElementById('messages');
    var messageInput = document.getElementById('messageInput');

    conn.onopen = function(e) {
        console.log("Connection established!");
    };

    conn.onmessage = function(e) {
        // Display received messages
        var message = document.createElement('p');
        message.textContent = e.data;
        messagesDiv.appendChild(message);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    };

    function sendMessage() {
        var message = messageInput.value;
        if (message) {
            conn.send(message);
            messageInput.value = '';  // Clear the input after sending
        }
    }
</script>

</body>
</html>
