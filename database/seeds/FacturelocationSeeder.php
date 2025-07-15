<?php

use Illuminate\Database\Seeder;
use  \App\Facturelocation;

class FacturelocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $facturelocations = [
            array(
                'typefacture_id'    => 1,
                'periodicite_id'    => 1,
                'datefacture'       => '2021/02/03',
                'objetfacture'      => 'Loyer',
                'nbremoiscausion'   => 1,
                'contrat_id'        => 1,
            ),
        ];

        foreach ($facturelocations as $facturelocation) {


            $facturelocation = Facturelocation::firstOrCreate(
                [
                    'typefacture_id' => $facturelocation['typefacture_id'],
                    'periodicite_id' => $facturelocation['periodicite_id'],
                    'datefacture' => $facturelocation['datefacture'],
                    'objetfacture' => $facturelocation['objetfacture'],
                    'nbremoiscausion' => $facturelocation['nbremoiscausion'],
                    'contrat_id' => $facturelocation['contrat_id'],
                ]
            );
        }
    }
}
