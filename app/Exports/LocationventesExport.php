<?php

namespace App\Exports;

use App\Typedocument;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class LocationventesExport implements FromView
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

        return view('excels.excellocationvente', [
            'data'                          =>isset($this->data['data'])  ?  $this->data['data']   : null,
            'total_apport_initial'          => isset($this->data['total_apport_initial']) ? $this->data['total_apport_initial'] : null,
            'total_apport_ponctuel'         => isset($this->data['total_apport_ponctuel']) ? $this->data['total_apport_ponctuel'] :  null,
        ]);
    }

}
