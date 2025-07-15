<?php

namespace App\Http\Controllers;

use App\Apportponctuel;
use App\Jobs\importApportponctuelFileJob;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class ApportponctuelController extends SaveModelController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $model = Apportponctuel::class;
    public $queryName = 'apportponctuels';
    protected $job = importApportponctuelFileJob::class;


}
