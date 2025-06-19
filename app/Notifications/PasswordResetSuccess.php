<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetSuccess extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Contraseña Restablecida Exitosamente')
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line('Tu contraseña ha sido restablecida con éxito.')
            ->action('Iniciar sesión', url('/login'))
            ->line('Si no solicitaste este cambio, por favor contacta con el soporte.');
    }
}