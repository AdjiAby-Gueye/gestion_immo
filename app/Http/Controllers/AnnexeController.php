<?php

namespace App\Http\Controllers;

use App\Outil;
use Throwable;
use App\Annexe;
use App\Entite;
use App\Contrat;
use App\Periode;
use Carbon\Carbon;
use App\Periodicite;
use App\Avisecheance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\SaveModelController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AnnexeController extends SaveModelController
{
    //
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $queryName = "annexes";
    protected $model = Annexe::class;
    protected $job = null;


   
}
