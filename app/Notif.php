<?php

namespace App;

use App\RefactoringItems\SaveModel;

class Notif extends SaveModel
{
    public function notif_perm_users()
    {
        return $this->hasMany(NotifPermUser::class);
    }
}
