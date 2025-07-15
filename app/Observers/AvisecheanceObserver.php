<?php

namespace App\Observers;

use App\Outil;
use App\Entite;
use App\Avisecheance;
use App\Helpers\MyHelper;


class AvisecheanceObserver
{
    /**
     * Handle the avisecheance "created" event.
     *
     * @param  \App\Avisecheance  $avisecheance
     * @return void
     */
    public function created(Avisecheance $avisecheance)
    {
        //
        info('Observer init : ' . $avisecheance->id);

        $entite = Entite::where("code", "RID")->first();

        if ($entite) {
            $ccopiesEmail = [];
            $raf = null;

            foreach ($entite->usersentite as $user) {
                if ($user->roles[0]->name == "RAF") {
                    $raf = $user->email;
                } else {
                    $ccopiesEmail[] = $user->email;
                }
            }
            $ccopiesEmail[] = "abou050793@gmail.com";
            $ccopiesEmail[] = "mansourpouye36@gmail.com";
            try {

                $link = "https://immo.erp.h-tsoft.com/signature-avis/$avisecheance->id";
                Outil::envoiEmail($raf, "Notification d'avis d'échéance", "Un nouveau avis d'échéance vient d'être créé.", 'echeanceridwan', null, $ccopiesEmail, null, $link);
                info('Observer fonctionne! Nouvel objet créé: ' . $avisecheance->id);
            } catch (\Exception $th) {
                $errors = "Une erreur est survenue";
                info('Observer ne fonctionne pas :  ' . $avisecheance->id);
                throw new \Exception($errors);
            }
        }
    }

    /**
     * Handle the avisecheance "updated" event.
     *
     * @param  \App\Avisecheance  $avisecheance
     * @return void
     */
    public function updated(Avisecheance $avisecheance)
    {
        //


    }

    /**
     * Handle the avisecheance "deleted" event.
     *
     * @param  \App\Avisecheance  $avisecheance
     * @return void
     */
    public function deleted(Avisecheance $avisecheance)
    {
        //
    }

    /**
     * Handle the avisecheance "restored" event.
     *
     * @param  \App\Avisecheance  $avisecheance
     * @return void
     */
    public function restored(Avisecheance $avisecheance)
    {
        //
    }

    /**
     * Handle the avisecheance "force deleted" event.
     *
     * @param  \App\Avisecheance  $avisecheance
     * @return void
     */
    public function forceDeleted(Avisecheance $avisecheance)
    {
        //
    }
}
