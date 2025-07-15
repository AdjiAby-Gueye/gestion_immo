<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Maileur extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $sujet;
    private $texte;
    private $page;
    private $contrat;
    private $copies;
    private $attachs;
    private $link;

    public function __construct($sujet, $texte, $page , $contrat = null ,$copies = null , $attachs = null , $link = null )
    {
        $this->sujet = $sujet;
        $this->texte = $texte;
        $this->page = $page;
        $this->contrat = $contrat;
        $this->copies = $copies;
        $this->attachs = $attachs;
        $this->link = $link;

    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        /* return $this->from('monsite@chezmoi.com')
            ->view('emails.maileur'); */

        $mail = $this->from('gestimmo@sertem-pm.com')
            ->subject($this->sujet)
            ->view('emails.' . $this->page, array(
                'texte' => $this->texte,
                'contrat' => $this->contrat,
                'link' => $this->link,
            ))

            ;
        if ($this->copies) {
            $mail->cc($this->copies);
        }
        if ($this->attachs) {
            foreach ($this->attachs as $atta) {
                $mail->attach($atta);
            }
        }


        return $mail;
    }
}
