<?php

namespace App;

use App\Attachement;
use Illuminate\Database\Eloquent\Model;

class Inbox extends Model
{
    public $table = "inboxs";

   
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function locataire()
    {
        return $this->belongsTo(Locataire::class);
    }
    public function appartement()
    {
        return $this->belongsTo(Appartement::class);
    }
    public function attachements()
    {
        return $this->hasMany(Attachement::class);
    }


}
