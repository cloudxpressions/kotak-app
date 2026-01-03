<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Test Email from ' . config('app.name'))
                    ->view('emails.test')
                    ->with([
                        'appName' => config('app.name'),
                        'dateTime' => now()->format('Y-m-d H:i:s'),
                    ]);
    }
}