<?php

namespace App\Exports;

use App\Client;
use App\Commande;
use App\Outil;
use App\Produit;
use App\QueryModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;


class UserExport implements FromView
{
    use Exportable;

    private $data;

    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $args = null;

        return view('excels.exceluser', [
            'data'                          =>isset($this->data['data'])                                   ?  $this->data['data']                              : null,
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
}
