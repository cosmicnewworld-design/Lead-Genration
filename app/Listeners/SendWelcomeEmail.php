<?php

namespace App\Listeners;

use App\Events\LeadCreated;
use App\Mail\WelcomeLeadEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use InteractsWithQueue;

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
    public function handle(LeadCreated $event): void
    {
        Mail::to($event->lead->email)->send(new WelcomeLeadEmail($event->lead));
    }
}
