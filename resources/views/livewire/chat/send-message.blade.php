
@assets
    @vite('resources/css/staffchat.css')
@endassets

<div class="scRender scStaffchat">
    {{-- The whole world belongs to you. --}}

    @if ($selectedConversation)

        <form wire:submit.prevent='sendMessage' action="" >
            <div class="chat-input">
            <div class="input-container">

                <input wire:model='body' type="text" id="sendMessage" class="control" placeholder="Write message">
                 <button type="submit" class="send-btn"><i class="fa-light fa-paper-plane"></i></button>
            </div>

            </div>
        </form>

    @endif

</div>
