<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;
    protected $guard_name = 'web';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function reguleclients()
    {
        return $this->hasMany(('App\ReguleClient'));
    }

    public function suivimarketings()
    {
        return $this->hasMany(('App\Suivimarketing'));
    }

    // public function entite()
    // {
    //     return $this->belongsTo('App\Entite');
    // }

    public function user_caisses()
    {
        return $this->hasMany(UserCaisse::class, 'user_id');
    }

    public function user_avec_entites()
    {
        return $this->hasMany(Entiteuser::class);
    }

    public function userdepartements()
    {
        return $this->hasMany(UserDepartement::class, 'user_id');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'user_id');
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class, 'affectation_id');
    }

    public function locataire()
    {
        return $this->belongsTo(Locataire::class);
    }

    public function entite()
    {
        return $this->belongsTo(Entite::class);
    }

    public function facturelocations()
    {
        return $this->hasMany(Facturelocation::class, 'user_id');
    }

    public function entitesuser()
    {
        return $this->belongsToMany(Entite::class, 'entite_user', 'user_id', 'entite_id');
    }
}
