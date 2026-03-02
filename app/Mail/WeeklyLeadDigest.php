<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class WeeklyLeadDigest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The collection of top leads.
     *
     * @var \Illuminate\Support\Collection
     */
    public $leads;

    /**
     * Create a new message instance.
     *
     * @param  \Illuminate\Support\Collection  $leads
     * @return void
     */
    public function __construct(Collection $leads)
    {
        $this->leads = $leads;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Weekly Lead Digest',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'emails.leads.digest',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
