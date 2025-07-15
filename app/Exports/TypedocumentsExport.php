<?php

namespace App\Exports;

use App\Typedocument;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class TypedocumentsExport implements FromView
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

        return view('excels.exceltypedocument', [
            'data'                          =>isset($this->data['data'])                                   ?  $this->data['data']                              : null,
        ]);
    }

}
