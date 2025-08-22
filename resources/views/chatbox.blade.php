@assets
    <link rel="stylesheet" href="{{ asset('client/assets/css/chatbot.css') }}">
    @vite('resources/css/chatbot.css')
@endassets
     <div class="scRender scChatbot">
    <div class="chatbox-container">
        <div id="chat-icon" class="chat-icon">
            <i class="fas fa-robot" style="color: white; font-size: 28px;"></i>
            <div class="chat-notification">1</div>
        </div>

        <div id="chat-window" class="chat-window">
            <div class="chat-header">
                <div class="ai-avatar">
                    🤖
                </div>
                <div class="chat-info" style="flex: 1;">
                    <div style="font-weight: bold; font-size: 16px;" id="chat-title">SE7EN AI Assistant</div>
                    <div style="font-size: 12px; opacity: 0.9;" id="chat-subtitle">Tư vấn phim & đặt vé thông minh</div>
                </div>
                <div class="chat-controls" style="display: flex; gap: 10px; align-items: center;">
                    <button id="mode-toggle" class="chat-mode-toggle">
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
                        <strong>🎬 SE7EN AI Assistant</strong><br>
                        Xin chào! Tôi là trợ lý AI của SE7ENCinema. Tôi có thể giúp bạn:
                        <br>• Tư vấn phim hay đang chiếu
                        <br>• Đặt vé xem phim
                        <br>• Thông tin lịch chiếu & giá vé
                        <br>• Khuyến mãi đặc biệt

                        <div class="message-actions">
                            <button class="action-btn" onclick="window.chatBot.suggestMovies()">🎥 Phim đang hot</button>
                            <button class="action-btn" onclick="window.chatBot.showSchedule()">📅 Lịch chiếu hôm nay</button>
                            <button class="action-btn" onclick="window.chatBot.showPromotions()">🎁 Khuyến mãi</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="typing-indicator" class="typing-indicator" style="display: none;">
                <div class="typing-dots">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
                <span id="typing-text">AI đang suy nghĩ...</span>
            </div>

            <div class="chat-input">
                <div class="input-container">
                    <input type="text" id="chat-message-input" placeholder="Hỏi về phim, đặt vé hoặc cần hỗ trợ gì...">
                    <div class="input-actions">
                        <button class="input-btn" title="Đính kèm file">
                            <i class="fas fa-paperclip"></i>
                        </button>
                        <button class="input-btn" title="Gửi hình ảnh">
                            <i class="fas fa-image"></i>
                        </button>
                    </div>
                    <button id="chat-send-btn" class="send-btn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                <div class="powered-by">
                    Được hỗ trợ bởi AI & nhân viên của <a href="#">SE7ENCinema</a>
                </div>
            </div>
        </div>
    </div>
     </div>
