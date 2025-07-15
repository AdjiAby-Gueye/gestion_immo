<?php

namespace App\Mail;

use App\Locataire;
use App\Outil;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PhpParser\Node\Expr\Cast\Array_;

class RappelPaiementLoyer extends Mailable
{
    use Queueable, SerializesModels;
    
    public Array $locataire;
    public string $montantLoyer;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Array $locataire , string $montantLoyer)
    {
        //

        $this->locataire = $locataire;
        $this->montantLoyer = $montantLoyer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $year = $date = $now = $delai = $delaiTab = $month = '';
        $date = $this->locataire['rappelpaiement'];
        $now = date("Y-m-d");
        $now = strtotime($now . "- 1 months");
        $now = date("Y-m-d", $now);
        $now = explode("-",$now);
        $year = $now[0];
        $delaiTab =  date('Y-m-d', strtotime(' + 7 days')); // On ajoute 7 jours
        $month = Outil::getMonthString($now[1]);
        $delai = $this->formateDateEngToFr($delaiTab);
        $periode = [
            'year' => $year,
            'month' => $month,
            'delai' => $delai
        ];
      
        return $this->from('magnitudoparvi1@gmail.com',"GESTION IMMOBILIER ERP")
                ->subject('RAPPEL DE PAIEMENT LOYER')
                ->with(['locatire' => $this->locataire , 'periode' => $periode , 'montant' => $this->montantLoyer])
                ->view('emails.rappelPaiementLoyer');
    }

    public function formateDateEngToFr($date)
    {
        $retour = "";
        $array = explode("-",$date);
        $retour = $array[2]."/".$array[1]."/".$array[0];
        return $retour;
    }
}
