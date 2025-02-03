<div id="chat-icon" onclick="toggleChat()">💬</div>
<div id="chat-box" style="display:none;">
    <select id="predefined-questions">
        <!-- Populate via AJAX -->
    </select>
    <button onclick="sendPredefined()">Send</button>
    <textarea id="user-question" placeholder="Type your question"></textarea>
    <button onclick="sendManual()">Send</button>
    <div id="chat-log"></div>
</div>
<script>
    // Toggle Chat Box Display
    function toggleChat() {
        const chatBox = document.getElementById("chat-box");
        chatBox.style.display = chatBox.style.display === "none" ? "block" : "none";

        // Load predefined questions when the chat box opens
        if (chatBox.style.display === "block") {
            loadQuestions();
        }
    }

    // Load predefined questions via AJAX
    function loadQuestions() {
        fetch('loadquestions.php') // Correct endpoint
            .then(response => {
                if (!response.ok) throw new Error('Failed to load questions');
                return response.json();
            })
            .then(questions => {
                const dropdown = document.getElementById('predefined-questions');
                dropdown.innerHTML = ''; // Clear existing options
                if (questions.length === 0) {
                    dropdown.innerHTML = `<option value="">No questions available</option>`;
                } else {
                    questions.forEach(q => {
                        const option = document.createElement('option');
                        option.value = q.id;
                        option.textContent = q.question;
                        dropdown.appendChild(option);
                    });
                }
            })
            .catch(error => console.error('Error loading questions:', error));
    }

    // Send a predefined question
    function sendPredefined() {
        const dropdown = document.getElementById('predefined-questions');
        const questionId = dropdown.value;

        if (!questionId) {
            alert('Please select a question to send.');
            return;
        }

        fetch('autoreply.php', { 
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `question_id=${encodeURIComponent(questionId)}`
        })
            .then(response => {
                if (!response.ok) throw new Error('Failed to send predefined question');
                return response.json();
            })
            .then(data => {
                const chatLog = document.getElementById('chat-log');
                const selectedQuestion = dropdown.options[dropdown.selectedIndex].text;
                chatLog.innerHTML += `<div><b>You:</b> ${selectedQuestion}</div>`;
                chatLog.innerHTML += `<div><b>Bot:</b> ${data.answer}</div>`;
            })
            .catch(error => console.error('Error sending predefined question:', error));
    }

    // Send a manual question
    function sendManual() {
        const message = document.getElementById('user-question').value.trim();

        if (!message) {
            alert('Please type a message before sending.');
            return;
        }

        const userId = 1; // Replace with actual user ID from session or logic

        fetch('sendquestion.php', { 
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `user_id=${encodeURIComponent(userId)}&message=${encodeURIComponent(message)}`
        })
            .then(response => {
                if (!response.ok) throw new Error('Failed to send manual question');
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    const chatLog = document.getElementById('chat-log');
                    chatLog.innerHTML += `<div><b>You:</b> ${message}</div>`;
                    document.getElementById('user-question').value = ''; // Clear input field
                } else {
                    console.error('Failed to process manual question:', data);
                }
            })
            .catch(error => console.error('Error sending manual question:', error));
    }
</script>
