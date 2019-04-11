<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailVerification extends Notification
{
  use Queueable;

  public function __construct(string $token) {
    $this->token = $token;
  }

  public function via($notifiable) {
    return ['mail'];
  }

  public function toMail($notifiable) {
    return (new MailMessage)
      ->line('You\'re receiving this email because you\'ve registered for an account and we can\'t wait to have you onboard as we\'re really excited you\'ve decided to give us a try.')
      ->line('As soon as your account is activated, you\'ll be able to login into our website and access our very fine state-of-the-art model for xray image analysis and pathology detector exceeding practicing radiologists, so click the link below in order to activate it.')
      ->action('Activate account', url('user/activation', $this->token))
      ->line('If you did not register for an account, or no longer wish to activate it, take no action. Simply delete this message and that will be the end of it. In case you have any questions, feel free to reach out to us or our support team at <a href="mailto:app.chexnet@gmail.com">app.chexnet@gmail.com</a>. We\'ll be happy to help.');
  }

  public function toArray($notifiable) {
    return [
      //
    ];
  }
}
