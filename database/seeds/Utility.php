<?php

namespace Database\Seeders;
use \Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class Utility extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function afterdataimport()
    {
    //    DB::connection('pgsql1')->beginTransaction();

        DB::connection('pgsql1')->commit();
    }



    public static function beforedataimport()
    {
        DB::connection('pgsql1')->beginTransaction();

       // DB::connection('pgsql1')->commit();
    }

}
