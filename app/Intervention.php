<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    public $table = "interventions";


    public function Typepiece()
    {
        return $this->belongsTo(Typepiece::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function Facture()
    {
        return $this->belongsTo(Facture::class);
    }


    public function Locataire()
    {
        return $this->belongsTo(Locataire::class);
    }

    public function Typeintervention()
    {
        return $this->belongsTo(Typeintervention::class);
    }
    public function Rapportintervention()
        {
            return $this->belongsTo(Rapportintervention::class);
        }

    public function Categorieintervention()
    {
        return $this->belongsTo(Categorieintervention::class);
    }

    public function Membreequipegestions()
    {
        return $this->belongsToMany(Membreequipegestion::class);
    }

    public function Demandeintervention()
    {
        return $this->belongsTo(Demandeintervention::class);
    }
    public function Etatlieu()
    {
        return $this->belongsTo(Etatlieu::class);
    }

    public function Prestataire()
    {
        return $this->belongsTo(Prestataire::class);
    }

    public function Membreequipegestion()
    {
        return $this->belongsTo(Membreequipegestion::class);
    }

    public function Questionnairesatisfaction()
    {
        return $this->hasMany(Questionnairesatisfaction::class);
    }

    public function commentaireinterventions()
    {
        return $this->hasMany(Commentaireintervention::class);
    }

    public function imageinterventions()
    {
        return $this->hasMany(Imageintervention::class);
    }

    public function factureinterventions()
    {
        return $this->hasMany(Factureintervention::class);
    }
}
