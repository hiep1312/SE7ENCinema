<?php

namespace App\Livewire\chat;

use App\Models\Conversation;
use App\Models\User;
use App\Models\Message;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class ChatList extends Component
{
    public $auth_id;
    public $conversations;
    public $receiverInstance;
    public $selectedConversation;
    public $receiverId;
    public $autoConnectToStaff = false; // New property

    public function mount($receiverId = null, $key = null, $autoConnect = false)
    {
        // Kiểm tra user đã đăng nhập chưa
        if (!auth()->check()) {
            $this->auth_id = null;
            $this->conversations = collect();
            return;
        }

        if ($receiverId) {
            $this->receiverId = $receiverId;
        }

        $this->auth_id = auth()->id();
        $this->autoConnectToStaff = $autoConnect;
        $this->loadConversations();

        // Auto redirect user to staff chat
        $this->autoRedirectUserToStaffChat();
    }

    private function autoRedirectUserToStaffChat()
    {
        $user = auth()->user();

        // Kiểm tra nếu user có role field trong database
        // Hoặc bạn có thể dùng cách khác để phân biệt user và staff
        if ($user && $this->isRegularUser($user)) {
            // Tìm staff có sẵn (role = 'staff' hoặc role = 'admin')
            $availableStaff = User::where('role', 'staff')
                ->orWhere('role', 'admin')
                ->first();

            if ($availableStaff) {
                // Tìm hoặc tạo conversation với staff
                $conversation = $this->findOrCreateConversationWithStaff($availableStaff->id);

                if ($conversation) {
                    // Auto select conversation với staff
                    $this->chatUserSelected($conversation, $availableStaff->id);

                    // Dispatch event to directly show chatbox instead of list
                    $this->dispatch('directStaffConnection');
                }
            }
        }
    }

    // New method for direct staff connection from chatbot
    #[On('connectToStaffDirectly')]
    public function connectToStaffDirectly()
    {
        $user = auth()->user();

        if (!$user) {
            // Handle unauthenticated user - maybe redirect to login
            return;
        }

        if ($this->isRegularUser($user)) {
            // Tìm staff có sẵn
            $availableStaff = User::where('role', 'staff')
                ->orWhere('role', 'admin')
                ->first();

            if ($availableStaff) {
                // Tìm hoặc tạo conversation với staff
                $conversation = $this->findOrCreateConversationWithStaff($availableStaff->id);

                if ($conversation) {
                    // Load conversation directly into chatbox
                    $this->chatUserSelected($conversation, $availableStaff->id);

                    // Dispatch event to skip chat list and go directly to chatbox
                    $this->dispatch('directStaffConnection');
                }
            }
        }
    }

    /**
     * Kiểm tra user có phải là user thường không (không phải staff/admin)
     */
    private function isRegularUser($user)
    {
        return !$user->hasRole('admin') && !$user->hasRole('staff');
    }

    /**
     * Kiểm tra user có phải là staff/admin không
     */
    private function isStaffOrAdmin($user)
    {
        return $user->hasRole('admin') || $user->hasRole('staff');
    }

    private function findOrCreateConversationWithStaff($staffId)
    {
        // Tìm conversation đã tồn tại
        $conversation = Conversation::where(function ($query) use ($staffId) {
            $query->where('sender_id', $this->auth_id)
                ->where('receiver_id', $staffId);
        })->orWhere(function ($query) use ($staffId) {
            $query->where('sender_id', $staffId)
                ->where('receiver_id', $this->auth_id);
        })->first();

        // Nếu chưa có thì tạo mới
        if (!$conversation) {
            $conversation = Conversation::create([
                'sender_id' => $this->auth_id,
                'receiver_id' => $staffId,
                'last_time_message' => now(),
            ]);

            // Tạo tin nhắn chào mừng tự động
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $staffId, // Staff gửi tin nhắn chào
                'receiver_id' => $this->auth_id,
                'body' => 'Xin chào! Tôi là nhân viên hỗ trợ. Tôi có thể giúp gì cho bạn?',
                'read' => 0,
            ]);

            // Reload conversations để hiển thị conversation mới
            $this->loadConversations();
        }

        return $conversation;
    }

    private function loadConversations()
    {
        // Chỉ load conversations khi user đã đăng nhập
        if (!$this->auth_id) {
            $this->conversations = collect();
            return;
        }

        $user = auth()->user();

        if ($user && $this->isStaffOrAdmin($user)) {
            // Admin/Staff: chỉ hiển thị conversations với users đã nhắn tin
            $this->conversations = Conversation::where(function ($query) {
                $query->where('sender_id', $this->auth_id)
                    ->orWhere('receiver_id', $this->auth_id);
            })
                ->whereHas('messages') // Chỉ lấy conversations có tin nhắn
                ->with(['messages' => function ($query) {
                    $query->latest()->limit(1);
                }])
                ->orderBy('last_time_message', 'DESC')
                ->get()
                ->filter(function ($conversation) {
                    // Chỉ hiển thị conversations với users (không phải staff với staff)
                    $otherUserId = $conversation->sender_id == $this->auth_id
                        ? $conversation->receiver_id
                        : $conversation->sender_id;

                    $otherUser = User::find($otherUserId);
                    return $otherUser && $this->isRegularUser($otherUser);
                });
        } else {
            // User thường: chỉ hiển thị conversations với staff
            $this->conversations = Conversation::where(function ($query) {
                $query->where('sender_id', $this->auth_id)
                    ->orWhere('receiver_id', $this->auth_id);
            })
                ->with(['messages' => function ($query) {
                    $query->latest()->limit(1);
                }])
                ->orderBy('last_time_message', 'DESC')
                ->get()
                ->filter(function ($conversation) {
                    // Chỉ hiển thị conversations với staff
                    $otherUserId = $conversation->sender_id == $this->auth_id
                        ? $conversation->receiver_id
                        : $conversation->sender_id;

                    $otherUser = User::find($otherUserId);
                    return $otherUser && $this->isStaffOrAdmin($otherUser);
                });
        }
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
        // Kiểm tra user đã đăng nhập chưa
        if (!auth()->check()) {
            return null;
        }

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

    #[Title('Chat - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        // Kiểm tra user đã đăng nhập chưa
        if (!auth()->check()) {
            return view('livewire.chat.chat-list', [
                'conversations' => collect(),
                'isLoggedIn' => false
            ]);
        }

        return view('livewire.chat.chat-list', [
            'conversations' => $this->conversations,
            'isLoggedIn' => true
        ]);
    }
}
