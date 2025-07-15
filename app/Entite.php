<?php

namespace App;

use App\User;
use App\Appartement;
use Illuminate\Database\Eloquent\Model;

class Entite extends Model
{
    //
    public $table = "entites";

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function appartements()
    {
        return $this->hasMany(Appartement::class);
    }

    public function gestionnaire() {

        return $this->belongsTo(User::class);
    }

    public function usersentite()
    {
        return $this->belongsToMany(User::class, 'entite_user', 'entite_id', 'user_id');
    }

    public function infobancaires()
    {
        return $this->hasMany(Infobancaire::class, 'entite_id');
    }
    public function contratproprietaire(){
        return $this->hasMany(Contratproprietaire::class);
    }
    public function activite(){
        return $this->hasMany(Activite::class);
    }
    public function entiteusers()
    {
        return $this->hasMany(Entiteuser::class, 'entite_id');
    }

}
