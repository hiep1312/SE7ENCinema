@assets
    @vite('resources/css/staffchat.css')
@endassets

<div class="scRender scStaffchat">
    <div class="chat_container">
        <div class="chat_list_container">
            @livewire('chat.chat-list')
        </div>

        <div class="chat_box_container">
            @livewire('chat.chatbox')
            @livewire('chat.send-message')
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatListContainer = document.querySelector('.chat_list_container');
            const chatBoxContainer = document.querySelector('.chat_box_container');
            const chatboxBody = document.querySelector('.chatbox_body');

            window.addEventListener('chatSelected', function() {
                if (window.innerWidth < 768) {
                    chatListContainer.style.display = 'none';
                    chatBoxContainer.style.display = 'block';
                }

                if (chatboxBody) {
                    chatboxBody.scrollTop = chatboxBody.scrollHeight;
                    const height = chatboxBody.scrollHeight;
                    Livewire.dispatch('updateHeight', { height: height });
                }
            });

            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    chatListContainer.style.display = 'block';
                    chatBoxContainer.style.display = 'block';
                }
            });

            document.addEventListener('click', function(event) {
                if (event.target.closest('.return')) {
                    chatListContainer.style.display = 'block';
                    chatBoxContainer.style.display = 'none';
                }
            });
        });
    </script>
</div>
