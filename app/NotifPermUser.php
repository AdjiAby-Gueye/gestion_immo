<?php

namespace App;

use App\RefactoringItems\SaveModel;
use Spatie\Permission\Models\Permission;

class NotifPermUser extends SaveModel
{
    public function notif()
    {
        return $this->belongsTo(Notif::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
