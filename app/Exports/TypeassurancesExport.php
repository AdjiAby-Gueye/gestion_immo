<?php

namespace App\Exports;

use App\Typeassurance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class TypeassurancesExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

  

    use Exportable;

    private $data;

    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $args = null;

        return view('excels.exceltypeassurance', [
            'data'                          =>isset($this->data['data'])                                   ?  $this->data['data']                              : null,
        ]);
    }

}
