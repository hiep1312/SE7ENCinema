let isAIMode = true;
let isMinimized = false;
let currentBookingData = null;
let GEMINI_API_KEY = "AIzaSyBbDvx7IhSwKJHutMdMEKNIlOxAytku0HU";
// Sửa API Configuration
const API_CONFIG = {
    gemini: {
        endpoint: 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent',
        apiKey: 'AIzaSyBbDvx7IhSwKJHutMdMEKNIlOxAytku0HU'
    }
};

const movies = [
    {
        id: 1,
        title: "Avatar: The Way of Water",
        genre: "Sci-Fi, Adventure",
        duration: "192 phút",
        rating: "9.2/10",
        price: "150,000đ",
        showtimes: ["10:00", "14:30", "18:00", "21:30"]
    },
    {
        id: 2,
        title: "Top Gun: Maverick",
        genre: "Action, Drama",
        duration: "131 phút",
        rating: "8.8/10",
        price: "130,000đ",
        showtimes: ["9:30", "13:00", "16:30", "20:00"]
    },
    {
        id: 3,
        title: "Spider-Man: No Way Home",
        genre: "Action, Adventure",
        duration: "148 phút",
        rating: "9.5/10",
        price: "140,000đ",
        showtimes: ["11:00", "15:30", "19:00", "22:00"]
    }
];

const chatIcon = document.getElementById('chat-icon');
const chatWindow = document.getElementById('chat-window');
const chatMessages = document.getElementById('chat-messages');
const messageInput = document.getElementById('chat-message-input');
const sendBtn = document.getElementById('chat-send-btn');
const modeToggle = document.getElementById('mode-toggle');
const typingIndicator = document.getElementById('typing-indicator');
const chatTitle = document.getElementById('chat-title');
const chatSubtitle = document.getElementById('chat-subtitle');

function initializeEventListeners() {
    chatIcon.addEventListener('click', toggleChat);
    sendBtn.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
    });
    modeToggle.addEventListener('click', toggleMode);

    document.querySelector('.chat-close').addEventListener('click', closeChat);
    document.querySelector('.chat-minimize').addEventListener('click', minimizeChat);
}

function toggleChat() {
    const isVisible = chatWindow.style.display === 'block';
    chatWindow.style.display = isVisible ? 'none' : 'block';
    if (!isVisible) {
        document.querySelector('.chat-notification').style.display = 'none';
        messageInput.focus();
    }
}

function closeChat() {
    chatWindow.style.display = 'none';
}

function minimizeChat() {
    isMinimized = !isMinimized;
    chatWindow.classList.toggle('chat-minimized', isMinimized);
}

function toggleMode() {
    isAIMode = !isAIMode;

    if (isAIMode) {
        modeToggle.innerHTML = '<i class="fas fa-user-tie"></i> Chuyển nhân viên';
        modeToggle.classList.remove('staff-mode');
        chatTitle.textContent = 'SE7EN AI Assistant';
        chatSubtitle.textContent = 'Tư vấn phim & đặt vé thông minh';
        addMessage('bot', '🤖 Đã chuyển về chế độ AI. Tôi sẵn sàng hỗ trợ bạn về phim ảnh và đặt vé!');
    } else {
        modeToggle.innerHTML = '<i class="fas fa-robot"></i> Chuyển AI';
        modeToggle.classList.add('staff-mode');
        chatTitle.textContent = 'Nhân viên hỗ trợ';
        chatSubtitle.textContent = 'Đang kết nối với nhân viên...';
        addMessage('staff', '👋 Xin chào! Tôi là nhân viên SE7ENCinema. Tôi sẽ hỗ trợ bạn những vấn đề mà AI chưa thể giải quyết được.');
    }
}

function sendMessage() {
    const message = messageInput.value.trim();
    if (!message) return;

    addMessage('user', message);
    messageInput.value = '';

    if (isAIMode) {
        handleAIResponse(message);
    } else {
        handleStaffResponse(message);
    }
}

function addMessage(type, content, actions = null) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}-message`;

    const contentDiv = document.createElement('div');
    contentDiv.className = 'message-content';
    contentDiv.innerHTML = content;

    if (actions) {
        const actionsDiv = document.createElement('div');
        actionsDiv.className = 'message-actions';
        actionsDiv.innerHTML = actions;
        contentDiv.appendChild(actionsDiv);
    }

    messageDiv.appendChild(contentDiv);
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function showTyping(text = 'AI đang suy nghĩ...') {
    document.getElementById('typing-text').textContent = text;
    typingIndicator.style.display = 'flex';
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function hideTyping() {
    typingIndicator.style.display = 'none';
}

// Sử dụng AI logic nội bộ thay vì API
async function handleAIResponse(message) {
    showTyping();

    const requestBody = {
        contents: [{
            parts: [{
                text: message
            }]
        }]
    };

    try {
        const response = await fetch(`${API_CONFIG.gemini.endpoint}?key=${GEMINI_API_KEY}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(requestBody),
        });

        if (!response.ok) {
            throw new Error(`API Error: ${response.status} ${response.statusText}`);
        }

        const data = await response.json();
        hideTyping();

        // Kiểm tra và xử lý phản hồi từ Gemini
        const aiResponse = data.candidates[0].content.parts[0].text;

        // Dù có API, bạn vẫn có thể giữ logic xử lý các tùy chọn sau đó
        addMessage('bot', aiResponse, `
      <button class="action-btn" onclick="suggestMovies()">🎥 Xem phim hay</button>
      <button class="action-btn" onclick="showBookingOptions()">🎫 Đặt vé</button>
      <button class="action-btn" onclick="toggleMode()">👨‍💼 Chuyển nhân viên</button>
    `);

    } catch (error) {
        hideTyping();
        console.error('Lỗi khi gọi API Gemini:', error);
        addMessage('bot', '🤖 Xin lỗi, tôi không thể kết nối với hệ thống. Vui lòng thử lại sau hoặc chuyển sang chế độ nhân viên hỗ trợ.');
    }
}


// Hàm tạo response thông minh dựa trên từ khóa
function generateSmartResponse(message) {
    const lowerMessage = message.toLowerCase();

    // Phân tích ý định người dùng và tạo response phù hợp
    if (lowerMessage.includes('đặt vé') || lowerMessage.includes('booking') || lowerMessage.includes('mua vé')) {
        return `🎫 Tuyệt vời! Tôi sẽ giúp bạn đặt vé xem phim.

Hiện tại chúng tôi đang có ${movies.length} phim hot:
${movies.map(movie => `• ${movie.title} - ${movie.price}`).join('\n')}

Bạn muốn đặt vé phim nào? Tôi sẽ hỗ trợ bạn chọn suất chiếu phù hợp nhất! 🎬`;
    }

    if (lowerMessage.includes('phim hay') || lowerMessage.includes('phim gì') || lowerMessage.includes('recommend')) {
        return `🎬 Đây là top phim đang HOT nhất tại SE7ENCinema:

🔥 **${movies[0].title}** - ${movies[0].rating}
${movies[0].genre} • ${movies[0].duration}
💰 Chỉ từ ${movies[0].price}

⭐ **${movies[1].title}** - ${movies[1].rating}
${movies[1].genre} • ${movies[1].duration}
💰 Giá cực ưu đãi ${movies[1].price}

🌟 **${movies[2].title}** - ${movies[2].rating}
${movies[2].genre} • ${movies[2].duration}
💰 Chỉ ${movies[2].price}

Phim nào bạn quan tâm nhất? Tôi sẽ tư vấn suất chiếu tốt nhất! 🍿`;
    }

    if (lowerMessage.includes('giá') || lowerMessage.includes('price') || lowerMessage.includes('bao nhiêu')) {
        return `💰 **Bảng giá vé SE7ENCinema hôm nay:**

🎫 **Theo loại ghế:**
• Ghế thường: 120,000đ - 150,000đ
• Ghế VIP: 180,000đ - 220,000đ
• Ghế đôi Sweetbox: 300,000đ - 350,000đ

⏰ **Ưu đãi theo thời gian:**
• Suất sáng (< 12h): GIẢM 20%
• Thứ 2,3,4: GIẢM 30%
• Sinh viên: GIẢM 20%

🎁 **Combo tiết kiệm:**
• 2 vé + bắp + nước = chỉ 299K (tiết kiệm 100K!)

Bạn muốn đặt vé suất nào để được giá tốt nhất? 💝`;
    }

    if (lowerMessage.includes('địa chỉ') || lowerMessage.includes('rạp') || lowerMessage.includes('location')) {
        return `📍 **Hệ thống SE7ENCinema - 3 địa điểm tiện lợi:**

🏢 **SE7EN Landmark 81**
Tầng 3, Landmark 81, Bình Thạnh
☎️ (028) 7777-1234 | 🕐 8:00-23:30

🛍️ **SE7EN Vincom Center**
Tầng 5, Vincom Center, Quận 1
☎️ (028) 7777-5678 | 🕐 9:00-23:00

🌟 **SE7EN Crescent Mall**
Tầng 4, Crescent Mall, Quận 7
☎️ (028) 7777-9999 | 🕐 9:00-22:30

Bạn muốn đến rạp nào? Tôi sẽ tư vấn lịch chiếu phù hợp! 🚗`;
    }

    if (lowerMessage.includes('lịch chiếu') || lowerMessage.includes('suất chiếu') || lowerMessage.includes('schedule')) {
        const today = new Date().toLocaleDateString('vi-VN');
        return `📅 **Lịch chiếu hôm nay (${today}):**

${movies.map(movie =>
            `🎬 **${movie.title}**
🕐 Suất: ${movie.showtimes.join(' • ')}
⭐ ${movie.rating} | 💰 ${movie.price}`
        ).join('\n\n')}

Bạn muốn xem suất nào? Tôi khuyên suất tối để có trải nghiệm tốt nhất! 🌙`;
    }

    if (lowerMessage.includes('khuyến mãi') || lowerMessage.includes('giảm giá') || lowerMessage.includes('promotion')) {
        return `🎁 **Khuyến mãi SIÊU HOT tháng này:**

🔥 **FLASH SALE T2-T3-T4**
GIẢM 30% tất cả vé | Mã: FLASH30

👨‍🎓 **Ưu đãi sinh viên**
GIẢM 20% + tặng combo bắp | Mã: STUDENT20

💕 **Combo cặp đôi**
2 vé + 2 bắp + 2 nước = 299K (tiết kiệm 100K!)

🎪 **Thành viên SE7EN**
Tích điểm đổi vé miễn phí + ưu đãi độc quyền

Bạn muốn sử dụng ưu đãi nào? Tôi sẽ áp dụng ngay! 💫`;
    }

    if (lowerMessage.includes('xin chào') || lowerMessage.includes('hello') || lowerMessage.includes('hi')) {
        return `👋 Xin chào! Tôi là AI Assistant của SE7ENCinema!

🎬 Tôi có thể giúp bạn:
• Tư vấn phim hay đang hot
• Đặt vé nhanh chóng
• Xem giá vé và khuyến mãi
• Thông tin lịch chiếu & địa chỉ rạp

Hôm nay bạn muốn xem phim gì? Hay cần tôi tư vấn phim hay nhất? 🍿✨`;
    }

    // Response mặc định cho các câu hỏi khác
    return `🤖 Tôi hiểu bạn đang quan tâm đến "${message}".

Để hỗ trợ bạn tốt nhất, tôi có thể giúp:
🎬 Tư vấn phim hay đang chiếu
🎫 Đặt vé với giá ưu đãi
💰 Xem bảng giá và khuyến mãi
📍 Thông tin địa chỉ rạp và lịch chiếu

Bạn muốn tôi hỗ trợ điều gì cụ thể? 😊`;
}


function processAIResponse(aiResponse, userMessage) {
    const lowerResponse = aiResponse.toLowerCase();
    const lowerMessage = userMessage.toLowerCase();

    if (lowerMessage.includes('đặt vé') || aiResponse.includes('đặt vé')) {
        addMessage('bot', aiResponse);
        setTimeout(() => showBookingOptions(), 1000);
    } else if (lowerMessage.includes('phim hay') || aiResponse.includes('phim')) {
        addMessage('bot', aiResponse);
        setTimeout(() => suggestMovies(), 1000);
    } else {
        addMessage('bot', aiResponse, `
            <button class="action-btn" onclick="suggestMovies()">🎥 Xem phim hay</button>
            <button class="action-btn" onclick="showBookingOptions()">🎫 Đặt vé</button>
            <button class="action-btn" onclick="toggleMode()">👨‍💼 Chuyển nhân viên</button>
        `);
    }
}

function handleStaffResponse(message) {
    showTyping('Nhân viên đang trả lời...');

    setTimeout(() => {
        hideTyping();
        addMessage('staff', `👨‍💼 Cảm ơn bạn đã liên hệ! Tôi đã ghi nhận yêu cầu: "${message}".
                <br><br>Tôi sẽ kiểm tra và phản hồi bạn trong vài phút. Trong lúc chờ đợi, bạn có thể:
                <br>• Tiếp tục chat với AI để được hỗ trợ nhanh
                <br>• Gọi hotline: 1900-7777 để được hỗ trợ ngay lập tức
                <br>• Email: support@se7encinema.com`, `
                    <button class="action-btn" onclick="toggleMode()">🤖 Quay lại AI</button>
                    <button class="action-btn" onclick="window.open('tel:19007777')">📞 Gọi hotline</button>
                `);
    }, Math.random() * 2000 + 1500);
}

function suggestMovies() {
    let movieList = '<strong>🔥 Top phim đang hot tại SE7ENCinema:</strong><br><br>';

    movies.forEach(movie => {
        movieList += `<div class="movie-card">
                    <div class="movie-title">${movie.title}</div>
                    <div class="movie-info">
                        📽️ ${movie.genre} • ⏱️ ${movie.duration}<br>
                        ⭐ ${movie.rating} • 💰 ${movie.price}
                    </div>
                </div>`;
    });

    addMessage('bot', movieList, `
                <button class="action-btn" onclick="showBookingOptions()">🎫 Đặt vé ngay</button>
                <button class="action-btn" onclick="showSchedule()">📅 Xem lịch chiếu</button>
            `);
}

function showBookingOptions() {
    let bookingHTML = '<strong>🎫 Chọn phim để đặt vé:</strong><br><br>';

    movies.forEach(movie => {
        bookingHTML += `<div class="movie-card">
                    <div class="movie-title">${movie.title}</div>
                    <div class="movie-info">💰 ${movie.price}</div>
                    <button class="book-btn" onclick="bookMovie(${movie.id})">Đặt vé phim này</button>
                </div>`;
    });

    addMessage('bot', bookingHTML);
}

function bookMovie(movieId) {
    const movie = movies.find(m => m.id === movieId);
    currentBookingData = { movieId };

    const bookingForm = `
                <strong>🎬 Đặt vé: ${movie.title}</strong>
                <div class="booking-form">
                    <div class="form-group">
                        <label class="form-label">Chọn suất chiếu:</label>
                        <select class="form-select" id="showtime">
                            ${movie.showtimes.map(time => `<option value="${time}">${time}</option>`).join('')}
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Số lượng vé:</label>
                        <select class="form-select" id="tickets">
                            <option value="1">1 vé</option>
                            <option value="2">2 vé</option>
                            <option value="3">3 vé</option>
                            <option value="4">4 vé</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Họ tên:</label>
                        <input type="text" class="form-input" id="customer-name" placeholder="Nhập họ tên">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Số điện thoại:</label>
                        <input type="tel" class="form-input" id="customer-phone" placeholder="Nhập số điện thoại">
                    </div>
                    <button class="book-btn" onclick="confirmBooking('${movie.title}', '${movie.price}')">
                        Xác nhận đặt vé
                    </button>
                </div>
            `;

    addMessage('bot', bookingForm);
}

function confirmBooking(movieTitle, price) {
    const showtime = document.getElementById('showtime').value;
    const tickets = document.getElementById('tickets').value;
    const customerName = document.getElementById('customer-name').value;
    const customerPhone = document.getElementById('customer-phone').value;

    if (!customerName || !customerPhone) {
        addMessage('bot', '❌ Vui lòng điền đầy đủ thông tin họ tên và số điện thoại!');
        return;
    }

    const totalPrice = parseInt(price.replace(/[,đ]/g, '')) * parseInt(tickets);
    const bookingCode = 'SE7EN' + Math.random().toString(36).substr(2, 6).toUpperCase();

    showTyping('Đang xử lý đặt vé...');

    setTimeout(() => {
        hideTyping();
        addMessage('bot', `
                    ✅ <strong>Đặt vé thành công!</strong>
                    <br><br>📋 <strong>Thông tin đặt vé:</strong>
                    <br>🎬 Phim: ${movieTitle}
                    <br>🕐 Suất chiếu: ${showtime}
                    <br>🎫 Số vé: ${tickets}
                    <br>👤 Tên: ${customerName}
                    <br>📱 SĐT: ${customerPhone}
                    <br>💰 Tổng tiền: ${totalPrice.toLocaleString()}đ
                    <br>🔖 Mã đặt vé: <strong>${bookingCode}</strong>

                    <br><br>📧 Thông tin chi tiết đã được gửi về SMS.
                    <br>Vui lòng đến rạp trước 15 phút để lấy vé!
                `, `
                    <button class="action-btn" onclick="suggestMovies()">🎥 Đặt thêm phim khác</button>
                    <button class="action-btn" onclick="showLocations()">📍 Xem địa chỉ rạp</button>
                `);
    }, 2000);
}

function showSchedule() {
    let scheduleHTML = '<strong>📅 Lịch chiếu hôm nay:</strong><br><br>';

    movies.forEach(movie => {
        scheduleHTML += `<div class="movie-card">
                    <div class="movie-title">${movie.title}</div>
                    <div class="movie-info">
                        🕐 Suất chiếu: ${movie.showtimes.join(' • ')}
                        <br>💰 Giá vé: ${movie.price}
                    </div>
                </div>`;
    });

    addMessage('bot', scheduleHTML, `
                <button class="action-btn" onclick="showBookingOptions()">🎫 Đặt vé ngay</button>
                <button class="action-btn" onclick="showPromotions()">🎁 Xem khuyến mãi</button>
            `);
}

function showPrices() {
    addMessage('bot', `
                <strong>💰 Bảng giá vé SE7ENCinema:</strong>
                <br><br>🎫 <strong>Giá vé theo loại ghế:</strong>
                <br>• Ghế thường: 120,000đ - 150,000đ
                <br>• Ghế VIP: 180,000đ - 220,000đ
                <br>• Ghế đôi Sweetbox: 300,000đ - 350,000đ

                <br><br>⏰ <strong>Giá vé theo thời gian:</strong>
                <br>• Suất sáng (trước 12h): Giảm 20%
                <br>• Suất chiều (12h-18h): Giá thường
                <br>• Suất tối (sau 18h): Giá thường

                <br><br>🎉 <strong>Ưu đãi đặc biệt:</strong>
                <br>• Thứ 2-3-4: Giảm 30% tất cả suất
                <br>• Sinh viên: Giảm 20% (có thẻ SV)
                <br>• Thành viên SE7EN: Tích điểm đổi vé
            `, `
                <button class="action-btn" onclick="showBookingOptions()">🎫 Đặt vé với giá ưu đãi</button>
                <button class="action-btn" onclick="showPromotions()">🎁 Xem thêm khuyến mãi</button>
            `);
}

function showPromotions() {
    addMessage('bot', `
                <strong>🎁 Khuyến mãi HOT tháng này:</strong>

                <div class="movie-card">
                    <div class="movie-title">🔥 FLASH SALE T2-T3-T4</div>
                    <div class="movie-info">
                        Giảm 30% tất cả vé xem phim<br>
                        Áp dụng: Thứ 2, 3, 4 hàng tuần<br>
                        Mã: FLASH30
                    </div>
                </div>

                <div class="movie-card">
                    <div class="movie-title">👨‍🎓 Ưu đãi sinh viên</div>
                    <div class="movie-info">
                        Giảm 20% khi xuất trình thẻ SV<br>
                        Tặng kèm combo bắp rang<br>
                        Mã: STUDENT20
                    </div>
                </div>

                <div class="movie-card">
                    <div class="movie-title">💕 Combo cặp đôi</div>
                    <div class="movie-info">
                        2 vé + 2 bắp rang + 2 nước = 299K<br>
                        Tiết kiệm 100K so với mua lẻ<br>
                        Mã: COUPLE299
                    </div>
                </div>
            `, `
                <button class="action-btn" onclick="showBookingOptions()">🎫 Đặt vé ưu đãi</button>
                <button class="action-btn" onclick="addMessage('bot', '📱 Để nhận mã giảm giá qua SMS, vui lòng cung cấp số điện thoại hoặc tham gia fanpage SE7ENCinema!')">📱 Nhận mã giảm giá</button>
            `);
}

function showLocations() {
    addMessage('bot', `
                <strong>📍 Hệ thống rạp SE7ENCinema:</strong>

                <div class="movie-card">
                    <div class="movie-title">🏢 SE7EN Landmark 81</div>
                    <div class="movie-info">
                        📍 Tầng 3, Landmark 81, Q.Bình Thạnh<br>
                        📞 (028) 7777 1234<br>
                        🕐 8:00 - 23:30 hàng ngày
                    </div>
                </div>

                <div class="movie-card">
                    <div class="movie-title">🛍️ SE7EN Vincom Center</div>
                    <div class="movie-info">
                        📍 Tầng 5, Vincom Center, Q.1<br>
                        📞 (028) 7777 5678<br>
                        🕐 9:00 - 23:00 hàng ngày
                    </div>
                </div>

                <div class="movie-card">
                    <div class="movie-title">🌟 SE7EN Crescent Mall</div>
                    <div class="movie-info">
                        📍 Tầng 4, Crescent Mall, Q.7<br>
                        📞 (028) 7777 9999<br>
                        🕐 9:00 - 22:30 hàng ngày
                    </div>
                </div>
            `, `
                <button class="action-btn" onclick="addMessage('bot', '🚗 Để xem chỉ đường chi tiết, bạn có thể sử dụng Google Maps hoặc liên hệ hotline 1900-7777')">🗺️ Chỉ đường</button>
                <button class="action-btn" onclick="showBookingOptions()">🎫 Đặt vé tại rạp</button>
            `);
}

function transferToStaff() {
    addMessage('bot', `
                🤖 Tôi hiểu rằng bạn cần hỗ trợ chuyên sâu hơn. Tôi sẽ chuyển bạn sang nhân viên hỗ trợ ngay!

                <br><br>Trong lúc chờ đợi, bạn có thể:
                <br>• Tiếp tục chat - nhân viên sẽ vào hỗ trợ
                <br>• Gọi hotline: 1900-7777 (miễn phí)
                <br>• Email: support@se7encinema.com
            `, `
                <button class="action-btn" onclick="toggleMode()">👨‍💼 Chuyển nhân viên ngay</button>
                <button class="action-btn" onclick="window.open('tel:19007777')">📞 Gọi hotline</button>
            `);
}

function generateSessionId() {
    return 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
}


function simulateStaffActivity() {
    if (!isAIMode && Math.random() > 0.7) {
        setTimeout(() => {
            if (!isAIMode) {
                const staffResponses = [
                    "👨‍💼 Tôi đang xem lại hệ thống để hỗ trợ bạn tốt nhất.",
                    "👨‍💼 Cảm ơn bạn đã chờ đợi, tôi đang xử lý yêu cầu của bạn.",
                    "👨‍💼 Bạn có cần thêm thông tin gì khác không?"
                ];
                addMessage('staff', staffResponses[Math.floor(Math.random() * staffResponses.length)]);
            }
        }, Math.random() * 10000 + 5000);
    }
}

function initializeApp() {
    initializeEventListeners();

    setTimeout(() => {
        if (chatWindow.style.display !== 'block') {
            document.querySelector('.chat-notification').style.display = 'flex';
        }
    }, 3000);

    chatIcon.addEventListener('click', () => {
        setTimeout(() => {
            if (chatWindow.style.display === 'block') {
                messageInput.focus();
            }
        }, 100);
    });

    messageInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    setInterval(simulateStaffActivity, 15000);
}

document.addEventListener('DOMContentLoaded', function () {
    initializeApp();
});
