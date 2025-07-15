<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InboxMail extends Mailable
{
    use Queueable, SerializesModels;


    private $sujet;
    private $texte;
    private $page;
    private $attachs;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sujet, $texte, $page, $attachs)
    {
        $this->sujet = $sujet;
        $this->texte = $texte;
        $this->page = $page;
        $this->attachs = $attachs;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->from('noreply@dmd.com')
        // ->subject($this->sujet)
        // ->with([
        //     'texte' => $this->texte,
        // ])
        // ->attach($this->attachments)
        // ->view('emails.' . $this->page);
        $mail = $this->from('noreply@dmd.com')
            ->subject($this->sujet)
            ->with(['texte' => $this->texte])
            ->view('emails.' . $this->page)
            
            ;

            foreach ($this->attachs as $atta) {
                $mail->attach($atta);
            }
        

        return $mail;
    }
}
