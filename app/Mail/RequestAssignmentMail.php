<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestAssignmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param $request
     * @param $user
     */
    public function __construct($request, $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('New Request Assignment')
            ->view('emails.request_assignment');
    }
}
