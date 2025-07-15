<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RappelActionEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The demo object instance.
     *
     * @var Demo
     */
    public $demo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('magnitudoparvi1@gmail.com')
            ->view('emails.demo_rappel')
            ->text('emails.rappel')
            ->with(
                [
                    'testVarOne' => '1',
                    'testVarTwo' => '2',
                ]);
//             ->attachData($this->pdf->output(),"rapport import fichier",[
//                'mime' => 'application/pdf']);
    }
}
