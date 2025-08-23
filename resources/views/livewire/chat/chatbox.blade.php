<div>
    @if ($selectedConversation)
        <div class="chatbox_header">
            <div class="return">
                <i class="bi bi-arrow-left"></i>
            </div>

            <div class="img_container">
                <img src="https://ui-avatars.com/api/?name={{ $receiverInstance->name }}" alt="">
            </div>

            <div class="name">
                {{ $receiverInstance->name }}
            </div>

            <div class="info">
                <div class="info_item">
                    <i class="bi bi-telephone-fill"></i>
                </div>
                <div class="info_item">
                    <i class="bi bi-image"></i>
                </div>
                <div class="info_item">
                    <i class="bi bi-info-circle-fill"></i>
                </div>
            </div>
        </div>

        <div class="chatbox_body" id="chatbox-body">
            @foreach ($messages as $message)
                <div class="msg_body {{ auth()->id() == $message->sender_id ? 'msg_body_me' : 'msg_body_receiver' }}"
                    style="width:80%;max-width:80%;max-width:max-content">
                    {{ $message->body }}
                    <div class="msg_body_footer">
                        <div class="date">
                            {{ $message->created_at->format('m: i a') }}
                        </div>
                        <div class="read">
                            @if($message->user->id === auth()->id())
                                @if($message->read == 0)
                                    <i class="bi bi-check2 status_tick"></i>
                                @else
                                    <i class="bi bi-check2-all text-primary"></i>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const chatboxBody = document.querySelector('.chatbox_body');

                if (chatboxBody) {
                    chatboxBody.addEventListener('scroll', function() {
                        if (this.scrollTop === 0) {
                            Livewire.dispatch('loadmore');
                        }
                    });
                }

                window.addEventListener('updatedHeight', function(event) {
                    const old = event.detail.height;
                    const newHeight = chatboxBody.scrollHeight;
                    chatboxBody.scrollTop = newHeight - old;

                    Livewire.dispatch('updateHeight', { height: newHeight });
                });

                window.addEventListener('rowChatToBottom', function() {
                    if (chatboxBody) {
                        chatboxBody.scrollTop = chatboxBody.scrollHeight;
                    }
                });

                document.addEventListener('click', function(event) {
                    if (event.target.closest('.return')) {
                        Livewire.dispatch('resetComponent');
                    }
                });

                window.addEventListener('markMessageAsRead', function() {
                    const statusTicks = document.querySelectorAll('.status_tick');
                    statusTicks.forEach(function(element) {
                        element.classList.remove('bi-check2');
                        element.classList.add('bi-check2-all', 'text-primary');
                    });
                });
            });
        </script>
    @else
        <div class="fs-4 text-center text-primary mt-5">
            No conversation selected
        </div>
    @endif
</div>
