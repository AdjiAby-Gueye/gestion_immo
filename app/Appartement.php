<?php

namespace App;

use App\Entite;
use Illuminate\Database\Eloquent\Model;

class Appartement extends Model
{
    public $table = "appartements";

    public function Proprietaire()
    {
        return $this->belongsTo(Proprietaire::class);
    }

    public function Niveauappartement()
    {
        return $this->belongsTo(Niveauappartement::class);
    }

    public function Immeuble()
    {
        return $this->belongsTo(Immeuble::class);
    }

    public function Typeappartement()
    {
        return $this->belongsTo(Typeappartement::class);
    }
    public function Etatappartement()
    {
        return $this->belongsTo(Etatappartement::class);
    }

    public function Frequencepaiementappartement()
    {
        return $this->belongsTo(Frequencepaiementappartement::class);
    }

    public function detailcompositions()
    {
        return $this->hasMany(Detailcomposition::class);
    }

    public function imageappartements()
    {
        return $this->hasMany(Imageappartement::class);
    }

    public function Locataires()
    {
        return $this->belongsToMany(Locataire::class);
    }

    public function locataire()
    {
        return $this->belongsTo(Locataire::class);
    }

    public function Contrats()
    {
        return $this->hasMany(Contrat::class);
    }

    public function Obligationadministratives()
    {
        return $this->hasMany(Obligationadministrative::class);
    }

    public function Paiementloyers()
    {
        return $this->hasMany(Paiementloyer::class);
    }

    public function Factures()
    {
        return $this->hasMany(Facture::class);
    }

    public function Annonces()
    {
        return $this->hasMany(Annonce::class);
    }

    public function Rapportinterventions()
    {
        return $this->hasMany(Rapportintervention::class);
    }
    public function Etatlieux()
    {
        return $this->hasMany(Etatlieu::class);
    }

    public function entite()
    {
        return $this->belongsTo(Entite::class);
    }

    public function ilot()
    {
        return $this->belongsTo(Ilot::class);
    }

    public function periodicite()
    {
        return $this->belongsTo(Periodicite::class);
    }


    // factureintervention
    public function factureinterventions()
    {
        return $this->hasMany(Factureintervention::class);
    }

    public function compositions()
    {
        return $this->hasMany(Composition::class);
    }
    public function contratproprietaire(){
        return $this->belongsTo(Contratproprietaire::class);
    }

    public function documentappartements()
    {
        return $this->hasMany(Documentappartement::class, 'appartement_id');
    }
}
