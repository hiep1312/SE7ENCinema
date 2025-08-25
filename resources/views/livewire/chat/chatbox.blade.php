@assets
    <script>
        const đáasasd = true;
    </script>
    <link rel="stylesheet" href="{{ asset('resources/css/staffchat.css') }}" @role('admin', 'staff') disabled @endrole>
    @role('user')
        <style>
                :root {
                    --green-primary: #28a745;
                    --green-secondary: #20c997;
                    --green-dark: #1e7e34;
                    --text-color-dark: #ffffff;
                    --text-color-light: #ffffff;
                    --background-color: #ffffff;
                    --border-color: #dee2e6;
                    --danger-color: #dc3545;
                    --background-color--chat:#201b1b;
                    --date-color: #ffe8e8;
                    --text-color-title: #000000;
                    --background-color--input: #ffffff;
                    --border-input--chat: #000000;
                    --sendMessage: #000000;
                }
        </style>
    @else
        <style>
            :root {
                --green-primary: #2889a7;
                    --green-secondary: #20c997;
                    --green-dark: #1e7e34;
                    --text-color-dark: #000000;
                    --text-color-light: #ffffff;
                    --background-color: #212529;
                    --border-color: #dee2e6;
                    --danger-color: #dc3545;
                    --background-color--chat:#d3d3d3;
                    --date-color: #000000;
                    --text-color-title: #ffffff;
                    --background-color--input: #212529;
                    --border-input--chat: #ffffff;
                    --sendMessage: #ffffff;

            }
        </style>
    @endrole
    @vite('resources/css/staffchat.css')
@endassets

<div class="scRender scStaffchat">
    @if (!$isLoggedIn)
        <div class="fs-4 text-center text-warning mt-5">
            <i class="bi bi-exclamation-triangle mb-3"></i>
            <p>Please log in to access chat</p>
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
        </div>
    @elseif ($selectedConversation)
        @role('admin', 'staff')
            {{-- <div class="chatbox_header">
                <div class="return">
                    <i class="bi bi-arrow-left"></i>
                </div>

                <div class="img_container">
                    <img src="https://avatar.iran.liara.run/public/boy" alt="">
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
            </div> --}}
        @endrole

        <div class="chatbox_body @role('admin', 'staff') '' @else no-header @endrole" id="chatbox-body">
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

                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('staff'))
                document.addEventListener('click', function(event) {
                    if (event.target.closest('.return')) {
                        Livewire.dispatch('resetComponent');
                    }
                });
                @endif

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
    @endif
</div>
