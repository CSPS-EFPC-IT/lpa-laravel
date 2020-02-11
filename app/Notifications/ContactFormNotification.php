<?php

namespace App\Notifications;

use App\Models\Lists\ContactFormSubject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactFormNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $subject;
    protected $body;
    protected $referrer;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->user = auth()->user();
        $this->subject = ContactFormSubject::findOrFail($data['subject_id']);
        $this->body = $data['body'];
        $this->referrer = $data['referrer'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $subject = "{$this->subject->name_fr} / {$this->subject->name_en}";

        return (new MailMessage)
            ->subject($subject)
            ->markdown('emails.contact_form', [
                'user'     => $this->user,
                'subject'  => $subject,
                'body'     => $this->body,
                'referrer' => $this->referrer,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable = null)
    {
        return [
            'user_id'    => $this->user->id,
            'subject_id' => $this->subject->id,
            'body'       => $this->body,
            'referrer'   => $this->referrer,
        ];
    }
}
