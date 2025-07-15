<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Maileurcourierrelance extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $sujet;
    private $periode;
    private $page;
    private $montant;
    private $montantpenalite;
    private $nombrepenalite;
    private $reservataire;

    public function __construct($sujet, $periode,$reservataire, $page , $montant = null, $montantpenalite = null, $nombrepenalite = null)
    {
        $this->sujet    = $sujet;
        $this->periode  = $periode;
        $this->montant  = $montant;
        $this->page     = $page;

        $this->montantpenalite     = $montantpenalite;
        $this->nombrepenalite      = $nombrepenalite;
        $this->reservataire        = $reservataire;

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
                'periode'         => $this->periode,
                'montant'         => $this->montant,
                'montantpenalite' => $this->montantpenalite,
                'nombrepenalite'  => $this->nombrepenalite,
                'reservataire'    => $this->reservataire
            ));
//        if ($this->copies) {
//            $mail->cc($this->copies);
//        }
//        if ($this->attachs) {
//            foreach ($this->attachs as $atta) {
//                $mail->attach($atta);
//            }
//        }


        return $mail;
    }
}

