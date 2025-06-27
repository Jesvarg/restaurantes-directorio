// Este archivo va en tu proyecto LARAVEL
class RestaurantChatbot {
    constructor() {
        this.chatbotApiUrl = 'http://localhost:5000'; // URL del chatbot Flask
        this.token = localStorage.getItem('chatbot_token');
        this.init();
    }

    init() {
        this.bindEvents();
        this.checkAuth();
    }

    bindEvents() {
        document.getElementById('chatbot-toggle').addEventListener('click', () => {
            this.toggleWidget();
        });

        document.getElementById('chatbot-send').addEventListener('click', () => {
            this.sendMessage();
        });

        document.getElementById('chatbot-input').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.sendMessage();
            }
        });
    }

    async checkAuth() {
        if (!this.token) {
            await this.authenticateWithLaravel();
        }
    }

    async authenticateWithLaravel() {
        try {
            const response = await fetch('/api/chatbot/auth', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();
            if (data.chatbot_token) {
                this.token = data.chatbot_token;
                localStorage.setItem('chatbot_token', this.token);
            }
        } catch (error) {
            console.error('Error autenticando chatbot:', error);
        }
    }

    async sendMessage() {
        const input = document.getElementById('chatbot-input');
        const message = input.value.trim();
        
        if (!message) return;

        this.addMessage(message, 'user');
        input.value = '';

        try {
            const response = await fetch(`${this.chatbotApiUrl}/chat/send`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${this.token}`
                },
                body: JSON.stringify({ message })
            });

            const data = await response.json();
            this.addMessage(data.response, 'bot');
        } catch (error) {
            this.addMessage('Lo siento, hubo un error. Intenta de nuevo.', 'bot');
        }
    }

    addMessage(text, sender) {
        const messagesContainer = document.getElementById('chatbot-messages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;
        messageDiv.textContent = text;
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    toggleWidget() {
        const widget = document.getElementById('chatbot-widget');
        widget.style.display = widget.style.display === 'none' ? 'block' : 'none';
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new RestaurantChatbot();
});