<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachement extends Model
{
    public $table = "attachements";

    protected $fillable = ['filepath', 'filename' ,'inbox_id'];


    public function inbox()
    {
        return $this->belongsTo(Inbox::class);
    }


}
