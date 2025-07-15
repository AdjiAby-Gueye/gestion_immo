<?php

namespace App\Exports;

use App\Typeappartement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class TypeappartementsExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    // public function collection()
    // {
    //     // return  Typeappartement::all();
    // }

    use Exportable;

    private $data;

    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $args = null;

        return view('excels.exceltypeappartement', [
            'data'                          =>isset($this->data['data'])                                   ?  $this->data['data']                              : null,
        ]);
    }

}
