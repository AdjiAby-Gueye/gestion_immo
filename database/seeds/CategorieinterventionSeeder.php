<?php

use App\Categorieintervention;
use Illuminate\Database\Seeder;

class
CategorieinterventionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categorieinterventions = [
            array(
                "designation" => "Plomberie",
            ),
            array(
                "designation" => "Electricité",
            ),
            array(
                "designation" => "Menuiserie",
            ),
            array(
                "designation" => "Maconnerie",
            ),
            array(
                "designation" => "Peinture",
            ),
            array(
                "designation" => "Nettoyage",
            )
        ];
        // supimer seulemt les  Categorieintervention et refaire sans doublure
      //  Categorieintervention::truncate();
       

        foreach ($categorieinterventions as $categorie) {
            $newcategorie = new Categorieintervention();
            $newcategorie->designation = $categorie['designation'];
            $newcategorie->save();
        }




       
    }
}
