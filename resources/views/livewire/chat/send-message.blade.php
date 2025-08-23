
    <div>
    {{-- The whole world belongs to you. --}}

    @if ($selectedConversation)

        <form wire:submit.prevent='sendMessage' action="" class="input-container">
            <div class="chat-input">
            <div class="input-container">

                <input wire:model='body' type="text" id="sendMessage" class="control" placeholder="Write message">
            <button type="submit" class="send-btn"></button>
            </div>

            </div>
        </form>

    @endif

</div>
