<?php

namespace App\Http\Controllers;

use App\Typeapportponctuel;
use Illuminate\Http\Request;

class TypeapportponctuelController extends SaveModelController
{
    public $model = Typeapportponctuel::class;
    public $queryName = 'typeapportponctuels';
}
