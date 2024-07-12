<?php

namespace App\Listeners;

use App\Services\Base\BaseService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class RegistrationEmailListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        $data = $event->eventData;

        // Send email to registered user
        BaseService::sendEmailGeneral(
            $data,
            'emails.users.registration',
            'Email Verification',
            $data['email'],
        );
    }
}
