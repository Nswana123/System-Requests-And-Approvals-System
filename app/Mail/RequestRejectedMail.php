<?php
namespace App\Mail;

use App\Models\request_tbl;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class RequestRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    
    /**
     * Create a new message instance.
     *
     * @param  request_tbl  $request
     * @return void
     */
    public function __construct(request_tbl $request)
    {
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Request Has Been Rejected')
                    ->view('emails.request_rejected');  // You will create this email view
    }
}
