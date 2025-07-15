<?php

namespace App\Http\Controllers;

use App\Modelcontrat;
use Illuminate\Http\Request;

class ModelcontratController extends SaveModelController
{
    public $model = Modelcontrat::class;
    public $queryName = 'modelcontrats';
}
