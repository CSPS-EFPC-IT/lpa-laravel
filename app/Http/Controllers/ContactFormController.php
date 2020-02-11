<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Models\OrganizationalUnit;
use App\Models\User\User;
use App\Notifications\ContactFormNotification;
use Exception;
use Illuminate\Support\Facades\Notification;

class ContactFormController extends APIController
{
    /**
     * Submit a contact form request to be sent by email
     * to the business mailbox.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function submit(ContactFormRequest $request)
    {
        // Retrieve contact form data from request.
        $data = $request->only([
            'subject_id',
            'body',
            'referrer',
        ]);

        // Handle email notification logic.
        $this->sendNotification($data);

        return $this->respondNoContent();
    }

    /**
     * Send an email notification to the business mailbox.
     *
     * @param  array $data
     * @return void
     */
    protected function sendNotification($data)
    {
        // Use business mailbox defined in configurations to send the notification to.
        if ($mailbox = config('mail.mailboxes.business')) {
            $notification = new ContactFormNotification($data);
            return Notification::route('mail', $mailbox)->notify($notification);
        }

        throw new Exception('No business mailbox was defined to receive the notifications.');
    }
}
