class Chatbot {
    constructor() {
        this.chatbotIcon = document.getElementById('chatbot-icon');
        this.chatbot = document.getElementById('chatbot');
        this.minimizeBtn = document.getElementById('minimize-btn');
        this.userInput = document.getElementById('user-input');
        this.sendBtn = document.getElementById('send-btn');
        this.chatMessages = document.getElementById('chat-messages');
        this.predefinedQuestionsContainer = document.getElementById('predefined-questions-container');

        this.setupEventListeners();
    }

    setupEventListeners() {
        this.chatbotIcon.addEventListener('click', () => this.toggleChatbot());
        this.minimizeBtn.addEventListener('click', () => this.toggleChatbot());
        this.sendBtn.addEventListener('click', () => this.sendMessage());
        this.userInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.sendMessage();
        });

        // Attach click event to dynamically loaded questions
        this.predefinedQuestionsContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('predefined-question')) {
                const questionText = e.target.textContent;
                this.handlePredefinedQuestion(questionText);
            }
        });
    }

    toggleChatbot() {
        this.chatbot.style.display = this.chatbot.style.display === 'none' ? 'block' : 'none';
        this.chatbotIcon.style.display = this.chatbot.style.display === 'none' ? 'flex' : 'none';
    }

    handlePredefinedQuestion(question) {
        console.log("Handling predefined question:", question);

        // Match button by content and trim whitespace
        const questionButton = Array.from(document.querySelectorAll('.predefined-question'))
            .find(btn => btn.textContent.trim() === question.trim());

        if (!questionButton) {
            console.error("No matching button found for question:", question);
            return;
        }

        const questionId = questionButton.dataset.id;

        if (!questionId) {
            console.error("No question ID found for question:", question);
            return;
        }

        console.log("Fetching answer for question ID:", questionId);

        // Fetch the answer from the server
        fetch(`get_answer.php?q_id=${questionId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log("Received data from server:", data);
                const answer = data.answer || "Maaf, tiada jawapan untuk soalan ini.";
                this.addMessage(question, 'user-message');
                this.addMessage(answer, 'bot-message');
            })
            .catch(error => {
                console.error("Error fetching answer:", error);
                this.addMessage("Maaf, terdapat masalah dalam mendapatkan jawapan.", 'bot-message');
            });
    }

    sendMessage() {
        const message = this.userInput.value.trim();
        if (message) {
            this.addMessage(message, 'user-message');
            this.addMessage('Terima kasih atas mesej anda. Admin akan membalas tidak lama lagi.', 'bot-message');
            this.userInput.value = '';
        }
    }

    addMessage(message, type) {
        const messageElement = document.createElement('div');
        messageElement.classList.add('message', type);
        messageElement.textContent = message;
        this.chatMessages.appendChild(messageElement);
        this.chatMessages.scrollTop = this.chatMessages.scrollHeight;
    }
}

// Initialize the chatbot when the page loads
document.addEventListener('DOMContentLoaded', () => {
    new Chatbot();
});