<?php

namespace App\Livewire\chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Livewire\Component;

class CreateChat extends Component
{
    public $users;
    public $message = 'hello how are you';

    public function checkconversation($receiverId)
    {
        $checkedConversation = Conversation::where(function ($query) use ($receiverId) {
            $query->where('receiver_id', auth()->user()->id)
                ->where('sender_id', $receiverId);
        })->orWhere(function ($query) use ($receiverId) {
            $query->where('receiver_id', $receiverId)
                ->where('sender_id', auth()->user()->id);
        })->get();

        if (count($checkedConversation) == 0) {
            $createdConversation = Conversation::create([
                'receiver_id' => $receiverId,
                'sender_id' => auth()->user()->id,
                'last_time_message' => now()
            ]);

            $createdMessage = Message::create([
                'conversation_id' => $createdConversation->id,
                'sender_id' => auth()->user()->id,
                'receiver_id' => $receiverId,
                'body' => $this->message
            ]);

            $createdConversation->last_time_message = $createdMessage->created_at;
            $createdConversation->save();

            return redirect()->route('chat');
        } else {
            session()->flash('info', 'Conversation already exists');
        }
    }

    public function createStaffConversation()
    {
        $staffUser = User::where('role', 'admin')->orWhere('role', 'staff')->first();

        if ($staffUser) {
            return $this->checkconversation($staffUser->id);
        }

        session()->flash('error', 'Không có nhân viên nào đang online');
    }

    public function render()
    {
        $this->users = User::where('id', '!=', auth()->user()->id)->get();
        return view('livewire.chat.create-chat');
    }
}
