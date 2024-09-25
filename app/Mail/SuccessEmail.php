<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SuccessEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    use Queueable, SerializesModels;

    public $saved;
    public $data;

    /**
     * Create a new message instance.
     *
     * @param $saved
     * @return void
     */
    public function __construct($saved, $data)
    {
        $this->saved = $saved;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('confirmation')
            ->with(['saved' => $this->saved , 'data'=>$this->data]);
    }
}
