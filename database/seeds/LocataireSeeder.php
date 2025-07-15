<?php

use App\Locataire;
use Illuminate\Database\Seeder;

class LocataireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locataires = [
            array(
                "prenom" => "Andrea",
                "nom" => "Nguessan",
                "telephoneportable1" => "774359823",
                "telephoneportable2" => "774359823",
                "telephonebureau" => "338452837",
                "email" => "andrea@gmail.com",
                "profession" => "Professeur",
                "age" => "26",
                "cni" => "19843678797304934629",
                "passeport" => "437863249237786434",
                "nomentreprise" => "",
                "adresseentreprise" => "",
                "ninea" => "",
                "documentninea" => "",
                "numerorg" => "",
                "documentnumerorg" => "",
                "documentstatut" => "",
                "personnehabiliteasigner" => "",
                "fonctionpersonnehabilite" => "",
                "nompersonneacontacter" => "",
                "prenompersonneacontacter" => "",
                "emailpersonneacontacter" => "",
                "telephone1personneacontacter" => "",
                "telephone2personneacontacter" => "",
                "etatlocataire" => "1",
                "entite_id" => 1,
            ),
            array(
                "prenom" => "HTSOFT",
                "nom" => "HTSOFT",
                "telephoneportable1" => "",
                "telephoneportable2" => "",
                "telephonebureau" => "",
                "email" => "",
                "profession" => "",
                "age" => "",
                "cni" => "",
                "passeport" => "",
                "nomentreprise" => "COD",
                "adresseentreprise" => "Foire",
                "ninea" => "973686923",
                "documentninea" => "lien",
                "numerorg" => "20211211",
                "documentnumerorg" => "lien...",
                "documentstatut" => "lien...",
                "personnehabiliteasigner" => "Rosalie",
                "fonctionpersonnehabilite" => "Directrice",
                "nompersonneacontacter" => "Nguessan",
                "prenompersonneacontacter" => "Rose",
                "emailpersonneacontacter" => "rose@gmail.com",
                "telephone1personneacontacter" => "7893479834",
                "telephone2personneacontacter" => "7893479834",
                "etatlocataire" => "1",
                "entite_id" => 1,
            )];

        foreach ($locataires as $locataire)
        {
            $newlocataire = Locataire::where('nom', $locataire['nom'])->where('prenom', $locataire['prenom'])->first();
            if (!$newlocataire) {
                $newlocataire = new Locataire();
            }
           
            $newlocataire->prenom = $locataire['prenom'];
            $newlocataire->nom = $locataire['nom'];
            $newlocataire->telephoneportable1 = $locataire['telephoneportable1'];
            $newlocataire->telephoneportable2 = $locataire['telephoneportable2'];
            $newlocataire->telephonebureau = $locataire['telephonebureau'];
            $newlocataire->email = $locataire['email'];
            $newlocataire->profession = $locataire['profession'];
            $newlocataire->age = $locataire['age'];
            $newlocataire->cni = $locataire['cni'];
            $newlocataire->passeport = $locataire['passeport'];
            $newlocataire->nomentreprise = $locataire['nomentreprise'];
            $newlocataire->adresseentreprise = $locataire['adresseentreprise'];
            $newlocataire->ninea = $locataire['ninea'];
            $newlocataire->documentninea = $locataire['documentninea'];
            $newlocataire->numerorg = $locataire['numerorg'];
            $newlocataire->documentnumerorg = $locataire['documentnumerorg'];
            $newlocataire->documentstatut = $locataire['documentstatut'];
            $newlocataire->personnehabiliteasigner = $locataire['personnehabiliteasigner'];
            $newlocataire->fonctionpersonnehabilite = $locataire['fonctionpersonnehabilite'];
            $newlocataire->nompersonneacontacter = $locataire['nompersonneacontacter'];
            $newlocataire->prenompersonneacontacter = $locataire['prenompersonneacontacter'];
            $newlocataire->emailpersonneacontacter = $locataire['emailpersonneacontacter'];
            $newlocataire->telephone1personneacontacter = $locataire['telephone1personneacontacter'];
            $newlocataire->telephone2personneacontacter = $locataire['telephone2personneacontacter'];
            $newlocataire->etatlocataire = $locataire['etatlocataire'];
            $newlocataire->entite_id = $locataire['entite_id'];
            $newlocataire->save();
        }
    }
}
