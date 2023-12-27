<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
/***/
use App\Models\User;

final class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Constructeur
     * @param User $user
     * @param string $token
     */
    public function __construct(private User $user, private string $token)
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = trans('notification.reset_password.subject');
        extract(collect(config('mail.from'))->only([ 'address', 'name'])->all());

        $this->from($address, $name);
        $this->to($this->user->email, $this->user->full_name);
        $this->subject($subject);

        $resetUrl = $this->user->getResetPasswordUri($this->token);

        return $this->view('auth.reset_password_notification', [
            'userName' => $this->user->full_name,
            'resetUrl' => url($resetUrl),
        ]);
    }
}
