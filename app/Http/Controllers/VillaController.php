<?php

namespace App\Http\Controllers;

use App\Appartement;
use Illuminate\Http\Request;
use App\Jobs\ImportVillaFileJob;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Jobs\ImportAppartementFileJob;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class VillaController extends SaveModelController
{
    //extends BaseController

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "villas";
    protected $model = Appartement::class;
    protected $job = ImportVillaFileJob::class;
}
