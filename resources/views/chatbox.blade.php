@assets
    @vite('resources/css/chatbot.css')
@endassets

<div class="scRender scChatbot">
    <div class="chatbox-container">
        <div id="chat-icon" class="chat-icon">
            <i class="fas fa-robot" style="color: white; font-size: 28px;"></i>
            <div class="chat-notification">1</div>
        </div>

        <div id="chat-window" class="chat-window">
            <!-- AI Chat Mode (Mặc định) -->
            <div id="ai-chat-mode" class="chat-mode active">
                <div class="chat-header">
                    <div class="ai-avatar">
                        <img src="{{ url('https://img.hoidap247.com/picture/question/20210901/large_1630466676430.jpg') }}" alt="img chuan seo">
                    </div>
                    <div class="chat-info" style="flex: 1;">
                        <div style="font-weight: bold; font-size: 16px;" id="chat-title">Trợ lý SE7EN</div>
                        <div style="font-size: 12px; opacity: 0.9;" id="chat-subtitle">Tư vấn phim & đặt vé thông minh</div>
                    </div>
                    <div class="chat-controls" style="display: flex; gap: 10px; align-items: center;">
                        <button id="switch-to-staff" class="chat-mode-toggle">
                            <i class="fas fa-user-tie"></i> Chuyển nhân viên
                        </button>
                        <button class="chat-minimize" style="background: none; border: none; color: white; cursor: pointer; font-size: 16px;">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button class="chat-close" style="background: none; border: none; color: white; cursor: pointer; font-size: 16px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="chat-messages" id="chat-messages">
                    <div class="message bot-message">
                        <div class="message-content">
                            <strong>Quân, Trợ lý ảo AI</strong><br>
                            Xin chào! Tôi là Quân, trợ lý ảo của SE7ENCinema. Tôi có thể giúp bạn:
                            <br>• Tư vấn tâm trạng bạn để chọn loại phim
                            <br>• Tư vấn cho bạn kiến thức về phim
                        </div>
                    </div>
                </div>

                <div id="typing-indicator" class="typing-indicator position-relative" style="display: none;">
                    <div class="typing-dots">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                    <span id="typing-text">tôi đã suy nghĩ ...</span>
                </div>

                <div class="chat-input">
                    <div class="input-container">
                        <input type="text" id="chat-message-input" placeholder="Hỏi về phim, đặt vé hoặc cần hỗ trợ gì...">
                        <button id="chat-send-btn" class="send-btn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    <div class="powered-by">
                        Được hỗ trợ bởi Quân AI & nhân viên của <a>SE7ENCinema</a>
                    </div>
                </div>
            </div>

            <!-- Staff Chat Mode (Livewire Components) -->
            <div id="staff-chat-mode" class="" style="display: none;">
                <div class="chat-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                    <div class="ai-avatar">
                        <i class="fas fa-user-headset" style="color: white; font-size: 24px;"></i>
                    </div>
                    <div class="chat-info" style="flex: 1;">
                        <div style="font-weight: bold; font-size: 16px;">Nhân viên hỗ trợ</div>
                        <div style="font-size: 12px; opacity: 0.9;">Trò chuyện với nhân viên thực</div>
                    </div>
                    <div class="chat-controls" style="display: flex; gap: 10px; align-items: center;">
                        <button id="switch-to-ai" class="chat-mode-toggle">
                            <i class="fas fa-robot"></i> Quay lại AI
                        </button>
                        <button class="chat-minimize" style="background: none; border: none; color: white; cursor: pointer; font-size: 16px;">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button class="chat-close" style="background: none; border: none; color: white; cursor: pointer; font-size: 16px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Tích hợp Livewire Chat Components -->
                <div class="staff-chat-container" style="height: calc(100% - 80px); display: flex;">
                    <!-- Chat List Component -->
                    <div id="chat-list-container" style="width: 100%; display: block;">
                        @livewire('chat.chat-list', ['receiverId' => null])
                    </div>

                    <!-- Chat Box Component -->
                    <div id="chat-box-container" style="width: 100%; display: none;">
                        @livewire('chat.chatbox')
                        @livewire('chat.send-message')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatIcon = document.getElementById('chat-icon');
    const chatWindow = document.getElementById('chat-window');
    const switchToStaffBtn = document.getElementById('switch-to-staff');
    const switchToAIBtn = document.getElementById('switch-to-ai');
    const aiChatMode = document.getElementById('ai-chat-mode');
    const staffChatMode = document.getElementById('staff-chat-mode');
    const chatListContainer = document.getElementById('chat-list-container');
    const chatBoxContainer = document.getElementById('chat-box-container');

    // Toggle chat window
    chatIcon.addEventListener('click', function() {
        if (chatWindow.classList.contains('active')) {
            chatWindow.classList.remove('active');
        } else {
            chatWindow.classList.add('active');
            document.querySelector('.chat-notification').style.display = 'none';
        }
    });

    // Close chat
    document.querySelectorAll('.chat-close').forEach(btn => {
        btn.addEventListener('click', function() {
            chatWindow.classList.remove('active');
        });
    });

    // Minimize chat
    document.querySelectorAll('.chat-minimize').forEach(btn => {
        btn.addEventListener('click', function() {
            chatWindow.classList.remove('active');
        });
    });

    // Switch to staff mode
    switchToStaffBtn.addEventListener('click', function() {
        aiChatMode.style.display = 'none';
        staffChatMode.style.display = 'flex';

        // Initialize Livewire if available
        if (typeof Livewire !== 'undefined') {
            Livewire.dispatch('initializeStaffChat');
        }
    });

    // Switch back to AI mode
    switchToAIBtn.addEventListener('click', function() {
        staffChatMode.style.display = 'none';
        aiChatMode.style.display = 'flex';

        // Reset staff chat
        if (chatListContainer) chatListContainer.style.display = 'block';
        if (chatBoxContainer) chatBoxContainer.style.display = 'none';

        if (typeof Livewire !== 'undefined') {
            Livewire.dispatch('resetComponent');
        }
    });

    // Listen for Livewire events from your chat components
    window.addEventListener('chatSelected', function() {
        // When user selects a conversation, show chatbox
        if (chatListContainer) chatListContainer.style.display = 'none';
        if (chatBoxContainer) chatBoxContainer.style.display = 'block';
    });

    // Listen for return to chat list
    window.addEventListener('resetComponent', function() {
        // Return to chat list view
        if (chatListContainer) chatListContainer.style.display = 'block';
        if (chatBoxContainer) chatBoxContainer.style.display = 'none';
    });

    // Initialize CinemaChat if the class exists
    if (typeof CinemaChat !== 'undefined') {
        window.cinemaChat = new CinemaChat();
    }
});
</script>
