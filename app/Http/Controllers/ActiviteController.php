<?php

namespace App\Http\Controllers;

use App\Activite;
use Illuminate\Http\Request;

class ActiviteController extends SaveModelController
{
    protected $model = Activite::class;
    protected $queryName = 'activites';
}
