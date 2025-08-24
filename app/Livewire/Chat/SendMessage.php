<?php

namespace App\Livewire\Chat;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class SendMessage extends Component
{
    public $selectedConversation;
    public $receiverInstance;
    public $body = '';
    public $createdMessage;
    private $messageSent = false;

    public function getListeners()
    {
        if (!auth()->check()) {
            return ['updateSendMessage', 'resetComponent'];
        }

        $auth_id = auth()->user()->id;
        return [
            "echo-private:chat.{$auth_id},MessageSent" => 'broadcastedMessageReceived',
            'updateSendMessage',
            'resetComponent'
        ];
    }

    #[On('broadcastedMessageReceived')]
    public function broadcastedMessageReceived($event)
    {
        // Chỉ nhận message từ người khác (không phải từ chính mình)
        if (isset($event['message']) && $this->selectedConversation) {
            $message = Message::find($event['message']);

            if ($message && $message->sender_id !== auth()->id()) {
                // Chỉ thêm message nếu thuộc conversation hiện tại
                if ($message->conversation_id == $this->selectedConversation->id) {
                    $this->dispatch('pushMessage', $message->id);
                }
            }
        }
    }

    #[On('updateSendMessage')]
    public function updateSendMessage(Conversation $conversation, User $receiver)
    {
        $this->selectedConversation = $conversation;
        $this->receiverInstance = $receiver;
        $this->messageSent = false; // Reset flag
    }

    #[On('resetComponent')]
    public function resetComponent()
    {
        $this->selectedConversation = null;
        $this->receiverInstance = null;
        $this->body = '';
        $this->messageSent = false;
    }

    public function sendMessage()
    {
        if (!auth()->check()) {
            return;
        }

        if (trim($this->body) === '') {
            return;
        }

        if (!$this->selectedConversation || !$this->receiverInstance) {
            return;
        }

        // Tránh gửi duplicate nếu đang trong quá trình gửi
        if ($this->messageSent) {
            return;
        }

        $this->messageSent = true;

        try {
            // Tạo message
            $this->createdMessage = Message::create([
                'conversation_id' => $this->selectedConversation->id,
                'sender_id' => auth()->user()->id,
                'receiver_id' => $this->receiverInstance->id,
                'body' => trim($this->body),
                'read' => 0,
            ]);

            // Update conversation's last message time
            $this->selectedConversation->update([
                'last_time_message' => now()
            ]);

            // Thêm message vào UI ngay lập tức (cho sender)
            $this->dispatch('pushMessage', $this->createdMessage->id);

            // Broadcast cho receiver
            broadcast(new MessageSent(
                auth()->user(),
                $this->createdMessage,
                $this->selectedConversation,
                $this->receiverInstance
            ));

            // Refresh chat list
            $this->dispatch('refresh')->to('chat.chat-list');

            // Reset body
            $this->body = '';
        } catch (\Exception $e) {
            // Log error nếu cần
            \Log::error('Send message error: ' . $e->getMessage());
        } finally {
            $this->messageSent = false;
        }
    }
    #[Title('Chat - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.chat.send-message');
    }
}
