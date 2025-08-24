<?php

namespace App\Livewire\chat;

use App\Events\MessageSent;
use App\Events\MessageRead;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;

class Chatbox extends Component
{
    public $selectedConversation;
    public $receiverInstance;
    public $messages;
    public $paginateVar = 10;
    public $height;
    public $messages_count;
    private $processedMessages = []; 

    public function getListeners()
    {
        // Kiểm tra user đã đăng nhập chưa
        if (!auth()->check()) {
            return ['resetComponent', 'loadConversation'];
        }

        $auth_id = auth()->user()->id;
        return [
            "echo-private:chat.{$auth_id},MessageSent" => 'broadcastedMessageReceived',
            "echo-private:chat.{$auth_id},MessageRead" => 'broadcastedMessageRead',
            'resetComponent',
            'loadConversation',
            'pushMessage',
            'loadmore',
            'updateHeight',
            'broadcastMessageRead'
        ];
    }

    #[On('resetComponent')]
    public function resetComponent()
    {
        $this->selectedConversation = null;
        $this->receiverInstance = null;
        $this->messages = collect();
        $this->processedMessages = [];
    }

    #[On('broadcastedMessageRead')]
    public function broadcastedMessageRead($event)
    {
        if ($this->selectedConversation) {
            if ((int) $this->selectedConversation->id === (int) $event['conversation_id']) {
                $this->dispatch('markMessageAsRead');
            }
        }
    }

    #[On('broadcastedMessageReceived')]
    public function broadcastedMessageReceived($event)
    {
        if (!isset($event['message']) || !$this->selectedConversation) {
            return;
        }

        // Check if this is the correct conversation
        if ((int) $this->selectedConversation->id !== (int) $event['conversation_id']) {
            return;
        }

        $messageId = $event['message'];

        // Tránh xử lý message trùng lặp
        if (in_array($messageId, $this->processedMessages)) {
            return;
        }

        $broadcastedMessage = Message::find($messageId);

        if (!$broadcastedMessage) {
            return;
        }

        // Chỉ xử lý message từ người khác (không phải từ chính mình)
        if ($broadcastedMessage->sender_id === auth()->id()) {
            return;
        }

        // Mark as processed
        $this->processedMessages[] = $messageId;

        // Mark as read
        $broadcastedMessage->read = 1;
        $broadcastedMessage->save();

        // Add to UI
        $this->pushMessage($messageId);

        // Refresh chat list
        $this->dispatch('refresh')->to('chat.chat-list');

        // Broadcast read status
        $this->dispatch('broadcastMessageRead');
    }

    #[On('broadcastMessageRead')]
    public function broadcastMessageRead()
    {
        // Kiểm tra các đối tượng tồn tại trước khi broadcast
        if ($this->selectedConversation && $this->receiverInstance) {
            broadcast(new MessageRead($this->selectedConversation->id, $this->receiverInstance->id));
        }
    }

    #[On('pushMessage')]
    public function pushMessage($messageId)
    {
        $newMessage = Message::find($messageId);
        if (!$newMessage) {
            return;
        }

        // Tránh thêm message trùng lặp
        $existingMessage = $this->messages->firstWhere('id', $messageId);
        if ($existingMessage) {
            return;
        }

        $this->messages->push($newMessage);
        $this->dispatch('rowChatToBottom');
    }

    #[On('loadmore')]
    public function loadmore()
    {
        // Kiểm tra selectedConversation tồn tại
        if (!$this->selectedConversation) {
            return;
        }

        $this->paginateVar = $this->paginateVar + 10;
        $this->messages_count = Message::where('conversation_id', $this->selectedConversation->id)->count();

        $this->messages = Message::where('conversation_id', $this->selectedConversation->id)
            ->skip($this->messages_count - $this->paginateVar)
            ->take($this->paginateVar)->get();

        $height = $this->height;
        $this->dispatch('updatedHeight', height: $height);
    }

    #[On('updateHeight')]
    public function updateHeight($height)
    {
        $this->height = $height;
    }

    #[On('loadConversation')]
    public function loadConversation(Conversation $conversation, User $receiver)
    {
        // Kiểm tra user đã đăng nhập chưa
        if (!auth()->check()) {
            return;
        }

        $this->selectedConversation = $conversation;
        $this->receiverInstance = $receiver;
        $this->processedMessages = []; // Reset processed messages

        $this->messages_count = Message::where('conversation_id', $this->selectedConversation->id)->count();

        $this->messages = Message::where('conversation_id', $this->selectedConversation->id)
            ->skip($this->messages_count - $this->paginateVar)
            ->take($this->paginateVar)->get();

        $this->dispatch('chatSelected');

        // Cập nhật trạng thái đã đọc
        Message::where('conversation_id', $this->selectedConversation->id)
            ->where('receiver_id', auth()->user()->id)
            ->where('read', 0)
            ->update(['read' => 1]);

        $this->dispatch('broadcastMessageRead');
    }

    #[Title('Chat - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        // Kiểm tra user đã đăng nhập chưa
        if (!auth()->check()) {
            return view('livewire.chat.chatbox', [
                'isLoggedIn' => false
            ]);
        }

        return view('livewire.chat.chatbox', [
            'isLoggedIn' => true
        ]);
    }
}
