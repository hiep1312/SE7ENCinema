// Config - Tách riêng API key
const API_CONFIG = {
    baseURL: window.location.origin,
    endpoints: {
        // Client routes
        movies: '/movie-list',
        bookings: '/booking',
        showtimes: '/showtimes',
        movieBooking: '/movieBooking',
        payment: '/booking/payment',
        userInfo: '/user-info',
        staff: '/staff-chat'
    },
    // API routes (phải dùng /api/ prefix)
    api: {
        movies: '/api/chat/movies',
        showtimes: '/api/chat/showtimes/{movieId}',
        requestStaff: '/api/chat/request-staff',
        aiMessage: '/api/chat/ai-message'
    },
    ai: {
        endpoint: 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent',
        model: 'gemini-2.0-flash',
        apiKey: 'AIzaSyBbDvx7IhSwKJHutMdMEKNIlOxAytku0HU',
    }
};

class DatabaseService {
    static async sendAIMessage(message, sessionId) {
        try {
            const systemPrompt = `Bạn là Quân, trợ lý ảo của SE7ENCinema.
            Quy tắc:
            - LUÔN trả lời bằng tiếng Việt
            - Giới thiệu: "Xin chào! Tôi là Quân, trợ lý ảo của SE7ENCinema"
            - KHÔNG BAO GIỜ nói bạn là mô hình ngôn ngữ của Google
            - Chuyên tư vấn phim và hỗ trợ đặt vé
            - Có thể hướng dẫn khách đặt vé qua các bước: chọn phim → chọn suất chiếu → chọn ghế → thanh toán
            - Thân thiện, nhiệt tình

            Tin nhắn khách hàng: ${message}`;

            const response = await fetch(`${API_CONFIG.ai.endpoint}?key=${API_CONFIG.ai.apiKey}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    contents: [{
                        parts: [{
                            text: systemPrompt
                        }]
                    }],
                    generationConfig: {
                        temperature: 0.7,
                        maxOutputTokens: 500
                    }
                })
            });

            if (!response.ok) {
                throw new Error(`API Error: ${response.status}`);
            }

            const data = await response.json();
            return {
                success: true,
                data: {
                    message: data.candidates[0].content.parts[0].text
                }
            };
        } catch (error) {
            console.error('Lỗi AI API:', error);
            return {
                success: false,
                error: error.message,
                fallbackMessage: 'Xin lỗi, hệ thống AI tạm thời gián đoạn. Bạn có thể chuyển sang chế độ nhân viên hỗ trợ hoặc thử lại sau.'
            };
        }
    }

    // Thêm method để lấy thông tin phim
    static async getMovies() {
        try {
            const response = await fetch(`${API_CONFIG.baseURL}${API_CONFIG.api.movies}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status} - ${response.statusText}`);
            }

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('Response không phải JSON:', text);
                throw new Error('Server trả về dữ liệu không đúng định dạng');
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Lỗi lấy danh sách phim:', error);
            return {
                success: false,
                error: error.message,
                fallback_movies: [
                    { id: 1, title: 'Phim đang cập nhật...', description: 'Danh sách phim đang được tải' }
                ]
            };
        }
    }

    // Lấy lịch chiếu
    static async getShowtimes(movieId) {
        try {
            const response = await fetch(`${API_CONFIG.baseURL}${API_CONFIG.api.showtimes}/${movieId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status}`);
            }

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Response không phải JSON');
            }

            return await response.json();
        } catch (error) {
            console.error('Lỗi lấy lịch chiếu:', error);
            return {
                success: false,
                error: error.message,
                fallback_message: 'Không thể tải lịch chiếu lúc này'
            };
        }
    }
}

class CinemaChat {
    constructor() {
        this.sessionId = this.generateSessionId();
        this.movies = [];
        this.currentBooking = null;
        this.init();
    }

    async init() {
        this.initializeElements();
        this.initializeEventListeners();
        this.showWelcomeMessage();
        await this.loadMovies();
    }

    initializeElements() {
        this.chatMessages = document.getElementById('chat-messages');
        this.messageInput = document.getElementById('chat-message-input');
        this.sendBtn = document.getElementById('chat-send-btn');
        this.typingIndicator = document.getElementById('typing-indicator');
    }

    initializeEventListeners() {
        this.sendBtn?.addEventListener('click', () => this.sendMessage());
        this.messageInput?.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        });
    }

    async loadMovies() {
        try {
            const result = await DatabaseService.getMovies();

            if (result.success && result.data) {
                this.movies = result.data;
            } else if (result.fallback_movies) {
                this.movies = result.fallback_movies;
                console.warn('Sử dụng danh sách phim dự phòng');
            } else {
                console.error('Không thể tải danh sách phim:', result.error);
                this.movies = [];
            }
        } catch (error) {
            console.error('Lỗi tải phim:', error);
            this.movies = [];
        }
    }

    generateSessionId() {
        return 'ai_session_' + Date.now() + '_' + Math.random().toString(36).substring(2, 9);
    }

    async sendMessage() {
        const message = this.messageInput.value.trim();
        if (!message) return;

        this.addMessage('user', message);
        this.messageInput.value = '';
        await this.handleAIResponse(message);
    }

    addMessage(type, content) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}-message`;

        const contentDiv = document.createElement('div');
        contentDiv.className = 'message-content';
        contentDiv.textContent = content;

        messageDiv.appendChild(contentDiv);
        this.chatMessages.appendChild(messageDiv);
        this.chatMessages.scrollTop = this.chatMessages.scrollHeight;
    }

    showTyping(text = 'Trợ lý Quân đang trả lời...') {
        const typingText = document.getElementById('typing-text');
        if (typingText) typingText.textContent = text;
        if (this.typingIndicator) this.typingIndicator.style.display = 'flex';
        this.chatMessages.scrollTop = this.chatMessages.scrollHeight;
    }

    hideTyping() {
        if (this.typingIndicator) this.typingIndicator.style.display = 'none';
    }

    // Kiểm tra ý định hỏi về phim
    detectMovieIntent(message) {
        const movieKeywords = ['phim đang chiếu', 'danh sách phim', 'có phim gì', 'movie list'];
        return movieKeywords.some(keyword =>
            message.toLowerCase().includes(keyword)
        );
    }

    // Hiển thị danh sách phim
    showAvailableMovies() {
        if (this.movies.length > 0) {
            const movieTitles = this.movies.map(movie => `"${movie.title}"`).join(', ');
            const message = `Dưới đây là một số phim đang chiếu tại rạp SE7ENCinema: ${movieTitles}. Bạn có quan tâm đến phim nào không?`;
            this.addMessage('bot', message);
        } else {
            this.addMessage('bot', 'Hiện tại không có phim nào đang chiếu hoặc danh sách phim đang được cập nhật. Vui lòng thử lại sau.');
        }
    }

    async handleAIResponse(message) {
        this.showTyping();

        // Kiểm tra ý định hỏi về phim trước
        if (this.detectMovieIntent(message)) {
            this.hideTyping();
            this.showAvailableMovies();
            return;
        }

        // Gửi tin nhắn cho AI
        try {
            const response = await DatabaseService.sendAIMessage(message, this.sessionId);
            this.hideTyping();

            if (response.success) {
                const aiResponse = this.processAIResponse(response.data.message);
                this.addMessage('bot', aiResponse);

                // Kiểm tra nếu có ý định đặt vé
                if (this.detectBookingIntent(message)) {
                    this.suggestBookingOptions();
                }
            } else {
                const fallbackMessage = response.fallbackMessage || 'Xin lỗi, tôi không thể kết nối với hệ thống. Vui lòng thử lại sau hoặc chuyển sang chế độ nhân viên hỗ trợ.';
                this.addMessage('bot', fallbackMessage);
            }
        } catch (error) {
            this.hideTyping();
            this.addMessage('bot', 'Có lỗi xảy ra. Vui lòng thử lại sau hoặc liên hệ nhân viên hỗ trợ.');
            console.error('Lỗi AI response:', error);
        }
    }

    processAIResponse(aiResponse) {
        let cleanResponse = aiResponse
            .replace(/[*#_`~]/g, '')
            .replace(/[^\w\sàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđ.,!?():-]/gi, '')
            .trim();

        return cleanResponse || 'Xin lỗi, tôi không hiểu câu hỏi của bạn. Bạn có thể diễn đạt lại không?';
    }

    detectBookingIntent(message) {
        const bookingKeywords = ['đặt vé', 'mua vé', 'booking', 'xem phim', 'suất chiếu', 'lịch chiếu'];
        return bookingKeywords.some(keyword =>
            message.toLowerCase().includes(keyword)
        );
    }

    suggestBookingOptions() {
        setTimeout(() => {
            this.addMessage('bot', 'Bạn có muốn tôi hướng dẫn đặt vé không? Tôi có thể giúp bạn:');
            this.addMessage('bot', '1. Xem danh sách phim đang chiếu');
            this.addMessage('bot', '2. Tìm kiếm suất chiếu phù hợp');
            this.addMessage('bot', '3. Hướng dẫn chọn ghế và thanh toán');
        }, 1000);
    }

    showWelcomeMessage() {
        // Hiển thị notification sau 3s
        setTimeout(() => {
            const chatWindow = document.getElementById('chat-window');
            if (!chatWindow.classList.contains('active')) {
                const notification = document.querySelector('.chat-notification');
                if (notification) notification.style.display = 'flex';
            }
        }, 3000);

        // Hiển thị tin nhắn chào mừng
        setTimeout(() => {
            this.addMessage('bot', 'Xin chào! Tôi là Quân, trợ lý ảo của SE7ENCinema. Tôi có thể giúp bạn tư vấn phim hay và hỗ trợ đặt vé. Hôm nay bạn muốn xem phim gì?');
        }, 1000);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    try {
        cinemaChat = new CinemaChat();
    } catch (error) {
        console.error('Lỗi khởi tạo chat system:', error);
    }
});

// Export cho window nếu cần
window.CinemaChat = CinemaChat;
