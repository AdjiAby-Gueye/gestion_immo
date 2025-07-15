<?php

use App\Equipegestion;
use Illuminate\Database\Seeder;

class EquipegestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $equipegestions = [
            array(
                "designation" => "Equipe 1",
            ),
            array(
                "designation" => "Equipe 2",
            )];

        foreach ($equipegestions as $equipegestion)
        {
            $newequipegestion = new Equipegestion();
            $newequipegestion->designation = $equipegestion['designation'] ;
            $newequipegestion->save();
        }
    }
}
