<?php

use App\Document;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documents = [
            array(
                "chemin" => "lien",
            ),
            array(
                "chemin" => "lien",
            )];

        foreach ($documents as $document)
        {
            $newdocument = new Document();
            $newdocument->chemin = $document['chemin'];
            $newdocument->save();
        }
    }
}
