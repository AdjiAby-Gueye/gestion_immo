<?php

namespace App\Http\Controllers;

use App\taxe;
use Illuminate\Http\Request;

class TaxeController extends SaveModelController
{
    protected $model = taxe::class;
    protected $queryName = 'taxes';
}
