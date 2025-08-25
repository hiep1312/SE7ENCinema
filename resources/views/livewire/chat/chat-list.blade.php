@assets
    @vite('resources/css/staffchat.css')
    <style>
        /* Khai báo các biến màu sắc */
:root {
    --primary-color: #0d6efd;
    --secondary-color: #6c757d;
    --text-color-dark: #242121;
    --text-color-light: #ffffff;
    --background-color-light: #212529;
    --border-color: #ffffff;
    --danger-color: #dc3545;
}

/* -------------------- Khung chứa chính -------------------- */
.chatlist {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    overflow: hidden;
    max-width: 400px;
    margin: 20px auto;
    background-color: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

/* -------------------- Header -------------------- */
.chatlist_header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    background-color: var(--background-color-light);
}

.chatlist_header .title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-color-dark);
}

.chatlist_header .img_container {
    width: 40px;
    height: 40px;
}

.chatlist_header .img_container img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--primary-color);
}

/* -------------------- Body -------------------- */
.chatlist_body {
    padding: 0;
}

/* -------------------- Item -------------------- */
.chatlist_item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.2s ease;
    background-color: var(--background-color-light);
}

.chatlist_item:last-child {
    border-bottom: none;
}

.chatlist_item:hover {
    background-color: var(--background-color-light);
    transform: translateY(-2px);
}

/* -------------------- Hình ảnh đại diện -------------------- */
.chatlist_img_container {
    width: 50px;
    height: 50px;
    flex-shrink: 0;
    margin-right: 15px;
}

.chatlist_img_container img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

/* -------------------- Thông tin cuộc trò chuyện -------------------- */
.chatlist_info {
    flex-grow: 1;
    overflow: hidden;
}

.chatlist_info .top_row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
}

.chatlist_info .list_username {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-color-dark);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.chatlist_info .date {
    font-size: 0.75rem;
    color: #ffffff;
    flex-shrink: 0;
    margin-left: 10px;
}

.chatlist_info .bottom_row {
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

/* -------------------- Badge đếm tin nhắn chưa đọc -------------------- */
.chatlist_info .unread_count.badge {
    background-color: var(--danger-color);
    color: #fff;
    font-size: 0.75rem;
    font-weight: 700;
    border-radius: 12px;
    padding: 3px 8px;
    min-width: 24px;
    text-align: center;
}

.chatlist_body .text-center {
    padding: 2rem;
    text-align: center;
    color: var(--secondary-color);
}

.chatlist_body .text-center h5 {
    margin-top: 1rem;
    font-weight: 600;
    color: var(--text-color-dark);
}

.chatlist_body .text-center p {
    color: var(--text-color-light);
    margin-bottom: 1.5rem;
}

.chatlist_body .text-center .btn {
    padding: 10px 20px;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 4px;
    text-decoration: none;
    transition: background-color 0.2s ease;
}

.chatlist_body .text-center .btn.btn-primary {
    background-color: var(--primary-color);
    border: 1px solid var(--primary-color);
    color: #fff;
}

.chatlist_body .text-center .btn.btn-primary:hover {
    background-color: #0a58ca;
    border-color: #0a58ca;
}
    </style>
@endassets

<div class="scRender scStaffchat">
    @if($isLoggedIn)
        <div class="chatlist_header">
            <div class="title">
                Hỗ trợ người dùng
            </div>
            <div class="img_container">
                <img src="https://avatar.iran.liara.run/public/boy" alt="">
            </div>
        </div>

        <div class="chatlist_body">
            @if (count($conversations) > 0)
                @foreach ($conversations as $conversation)
                    <div class="chatlist_item" wire:key='{{$conversation->id}}'
                         wire:click="$dispatch('chatUserSelected', { conversation: {{$conversation}}, receiverId: {{$this->getChatUserInstance($conversation, 'id')}} })">
                        <div class="chatlist_img_container">
                            <img src="https://avatar.iran.liara.run/public/boy" alt="">
                        </div>

                        <div class="chatlist_info">
                            <div class="top_row">
                                <div class="list_username">{{ $this->getChatUserInstance($conversation, 'name') }}</div>
                                <span class="date text-white">
                                    {{ $conversation->messages->last()?->created_at->shortAbsoluteDiffForHumans() }}
                                </span>
                            </div>

                            <div class="bottom_row">
                                @if(auth()->check())
                                    @php
                                        $unreadCount = count($conversation->messages->where('read',0)->where('receiver_id', auth()->user()->id));
                                    @endphp

                                    @if($unreadCount > 0)
                                        <div class="unread_count badge rounded-pill text-light bg-danger">
                                            {{ $unreadCount }}
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
            @endif
        </div>
    @else
        <div class="chatlist_body">
            <div class="text-center mt-3">
                <div class="mb-3">
                    <i class="fas fa-sign-in-alt fa-3x text-muted"></i>
                </div>
                <h5>Please log in to view chats</h5>
                <p class="text-muted">You need to be logged in to access the chat feature.</p>
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            </div>
        </div>
    @endif
</div>
