<div>
    <div class="chatlist_header">
        <div class="title">
            Chat
        </div>
        <div class="img_container">
            <img src="https://ui-avatars.com/api/?background=0D8ABC&color=fff&name={{auth()->user()->name}}" alt="">
        </div>
    </div>

    <div class="chatlist_body">
        @if (count($conversations) > 0)
            @foreach ($conversations as $conversation)
                <div class="chatlist_item" wire:key='{{$conversation->id}}'
                     wire:click="$dispatch('chatUserSelected', { conversation: {{$conversation}}, receiverId: {{$this->getChatUserInstance($conversation, 'id')}} })">
                    <div class="chatlist_img_container">
                        <img src="https://ui-avatars.com/api/?name={{$this->getChatUserInstance($conversation, 'name')}}" alt="">
                    </div>

                    <div class="chatlist_info">
                        <div class="top_row">
                            <div class="list_username">{{ $this->getChatUserInstance($conversation, 'name') }}</div>
                            <span class="date">
                                {{ $conversation->messages->last()?->created_at->shortAbsoluteDiffForHumans() }}
                            </span>
                        </div>

                        <div class="bottom_row">
                            @php
                                $unreadCount = count($conversation->messages->where('read',0)->where('receiver_id',Auth()->user()->id));
                            @endphp

                            @if($unreadCount > 0)
                                <div class="unread_count badge rounded-pill text-light bg-danger">
                                    {{ $unreadCount }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center mt-3">You have no conversations</div>
        @endif
    </div>
</div>
