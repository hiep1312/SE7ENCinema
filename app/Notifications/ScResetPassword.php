<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ScResetPassword extends ResetPassword implements ShouldQueue
{
    use Queueable;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     */
    public function __construct(#[\SensitiveParameter] $token, protected User $user)
    {
        parent::__construct($token);
    }

    /**
     * Get the reset password notification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('Đặt lại mật khẩu của bạn')
            ->view('livewire.client.auth.templateMail.reset-password', array_merge(compact('url'), ['user' => $this->user]));
    }
}
