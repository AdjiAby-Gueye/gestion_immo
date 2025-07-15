<?php

namespace App\Exports;

use App\Typedocument;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class EtatencaissementExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */



    use Exportable;

    private $data;

    public function __construct($data = null)
    {
       // dd($data);
        $this->data = $data;
    }

    public function view(): View
    {
        $args = null;
       // dd($this->data);

        return view('excels.exceletatencaissement', [
            'data'                          =>isset($this->data)                                   ?  $this->data                             : null,
        ]);
    }

}
