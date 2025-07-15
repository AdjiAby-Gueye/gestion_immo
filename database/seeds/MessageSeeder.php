<?php

use App\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $messages = [
            array(
                "objet" => "Rappel mensualité",
                "contenu" => "Bonjour vous n'avez pas encore payé votre mensualité",
            ),
            array(
                "objet" => "Frais supplementaires",
                "contenu" => "si vous depassez le 15 vous allez devoir payer 2000 fr de frais",
            )];

        foreach ($messages as $message)
        {
            $newmessage = new Message();
            $newmessage->objet = $message['objet'];
            $newmessage->contenu = $message['contenu'];
            $newmessage->save();
        }
    }
}
