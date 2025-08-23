<?php

namespace App\Livewire\chat;

use App\Models\Conversation;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;

class ChatList extends Component
{
    public $auth_id;
    public $conversations;
    public $receiverInstance;
    public $selectedConversation;
    public $receiverId;

    public function mount($receiverId = null, $key = null)
    {
        if ($receiverId) {
            $this->receiverId = $receiverId;
        }

        $this->auth_id = auth()->id();
        $this->loadConversations();
    }

    private function loadConversations()
    {
        $this->conversations = Conversation::where('sender_id', $this->auth_id)
            ->orWhere('receiver_id', $this->auth_id)
            ->orderBy('last_time_message', 'DESC')
            ->get();
    }

    #[On('resetComponent')]
    public function resetComponent()
    {
        $this->selectedConversation = null;
        $this->receiverInstance = null;
    }

    #[On('refresh')]
    public function refresh()
    {
        $this->loadConversations();
    }

    #[On('chatUserSelected')]
    public function chatUserSelected(Conversation $conversation, $receiverId)
    {
        $this->selectedConversation = $conversation;
        $this->receiverInstance = User::find($receiverId);

        $this->dispatch('loadConversation', $conversation, $this->receiverInstance)->to('chat.chatbox');
        $this->dispatch('updateSendMessage', $conversation, $this->receiverInstance)->to('chat.send-message');
    }

    public function getChatUserInstance(Conversation $conversation, $request)
    {
        $this->auth_id = auth()->id();

        if ($conversation->sender_id == $this->auth_id) {
            $this->receiverInstance = User::firstWhere('id', $conversation->receiver_id);
        } else {
            $this->receiverInstance = User::firstWhere('id', $conversation->sender_id);
        }

        if ($this->receiverInstance) {
            return $this->receiverInstance->$request ?? null;
        }

        return null;
    }

    public function render()
    {
        $user = auth()->user();
        if ($user && ($user->hasRole('admin') || $user->hasRole('staff'))) {
            // Nếu là admin hoặc staff thì hiển thị tất cả cuộc trò chuyện
            return view('livewire.chat.chat-list', [
                'conversations' => $this->conversations
            ]);
        } else {
            // Nếu là user thì chỉ hiển thị các cuộc trò chuyện với nhân viên
            return view('livewire.chat.chat-list', [
                'conversations' => $this->conversations->filter(function ($conversation) {
                    // Kiểm tra receiver có tồn tại trước khi truy cập role
                    return $conversation->receiver && $conversation->receiver->role === 'staff';
                })
            ]);
        }
    }
}
