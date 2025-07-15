<?php

namespace App\GraphQL\Type;

use App\Avenant;
use App\Ilot;


use App\Outil;
use Psy\Util\Str;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use App\RefactoringItems\RefactGraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class AppartementType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Appartement',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'etat' => ['type' => Type::id(), 'description' => ''],

                'codeappartement' => ['type' => Type::string(), 'description' => ''],
                'isdemanderesiliation' => ['type' => Type::string(), 'description' => ''],
                'nom' => ['type' => Type::string(), 'description' => ''],
                'etatlieu' => ['type' => Type::string(), 'description' => ''],
                // 'etatlieu_text' => ['type' => Type::string(), 'description' => ''],
                // 'etatlieu_badge'=> ['type' => Type::string(), 'description' => ''],
                // 'etatlieux' => ['type' =>  GraphQL::type('Etatlieu')],

                'entite_id' => ['type' => Type::int()],
                'entite' => ['type' =>  GraphQL::type('Entite')],

                'contratproprietaire_id' => ['type' => Type::int()],
                'contratproprietaire' => ['type' =>  GraphQL::type('Contratproprietaire')],
                'commissionvaleur' => ['type' => Type::int()],
                'commissionpourcentage' => ['type' => Type::int()],
                'tva' => ['type' => Type::int()],
                'tvamountant' => ['type' => Type::int()],
                'tlvmountant' => ['type' => Type::int()],
                'brs' => ['type' => Type::int()],
                'tlv' => ['type' => Type::int()],
                'montantloyer' => ['type' => Type::int()],
                'montantcaution' => ['type' => Type::int()],



                'lot' => ['type' => Type::string()],
                'prixvilla' => ['type' => Type::string()],
                'prixvillaformat' => ['type' => Type::string(), 'description' => ''],
                'acomptevilla' => ['type' => Type::string()],
                'acompte_format' => ['type' => Type::string()],
                'acompte_percent' => ['type' => Type::string()],

                'maturite' => ['type' => Type::int()],
                'ilot_id' => ['type' => Type::int()],
                'position' => ['type' => Type::int()],

                'ilot' => ['type' => GraphQL::type('Ilot')],
                'periodicite_id' => ['type' => Type::int()],
                'periodicite' => ['type' => GraphQL::type('Periodicite')],


                'isassurance' => ['type' => Type::string(), 'description' => ''],
                'niveau' => ['type' => Type::string(), 'description' => ''],
                'iscontrat' => ['type' => Type::string(), 'description' => ''],
                'islocataire' => ['type' => Type::string(), 'description' => ''],
                'image' => ['type' => Type::string(), 'description' => ''],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'superficie' => ['type' => Type::string()],
                'niveauappartement' => ['type' =>  GraphQL::type('Niveauappartement')],
                'proprietaire' => ['type' =>  GraphQL::type('Proprietaire')],
                'typeappartement' => ['type' =>  GraphQL::type('Typeappartement')],
                'frequencepaiementappartement' => ['type' =>  GraphQL::type('Frequencepaiementappartement')],
                'etatappartement' => ['type' =>  GraphQL::type('Etatappartement')],
                'immeuble_id' => ['type' => Type::string(), 'description' => ''],
                'proprietaire_id' => ['type' => Type::string(), 'description' => ''],
                'typeappartement_id' => ['type' => Type::int(), 'description' => ''],
                'frequencepaiementappartement_id' => ['type' => Type::string(), 'description' => ''],
                'etatappartement_id' => ['type' => Type::int(), 'description' => ''],
                'locataire_id' => ['type' => Type::int(), 'description' => ''],
                'pieceappartements' => ['type' => Type::listOf(GraphQL::type('Pieceappartement')), 'description' => ''],
                'imageappartements' => ['type' => Type::listOf(GraphQL::type('Imageappartement')), 'description' => ''],
                'locataires' => ['type' => Type::listOf(GraphQL::type('Locataire')), 'description' => ''],
                'locataire' => ['type' => GraphQL::type('Locataire'), 'description' => ''],
                'contrats' => ['type' => Type::listOf(GraphQL::type('Contrat')), 'description' => ''],
                // 'obligationadministratives' => ['type' => Type::listOf(GraphQL::type('Obligationadministrative')), 'description' => ''],
                'paiementloyers' => ['type' => Type::listOf(GraphQL::type('Paiementloyer')), 'description' => ''],
                'factures' => ['type' => Type::listOf(GraphQL::type('Facture')), 'description' => ''],
                'annonces' => ['type' => Type::listOf(GraphQL::type('Annonce')), 'description' => ''],
                // 'rapportinterventions' => ['type' => Type::listOf(GraphQL::type('Rapportintervention')), 'description' => ''],
                'etatlieux' => ['type' => Type::listOf(GraphQL::type('Etatlieu')), 'description' => ''],

                'factureintervention_id' => ['type' => Type::int()],
                //'factureintervention' => ['type' =>  GraphQL::type('Factureintervention')],
                // 'factureinterventions' => ['type' => Type::listOf(GraphQL::type('Factureintervention')), 'description' => ''],
                'compositions' => ['type' => Type::listOf(GraphQL::type('Composition')), 'description' => ''],


                'lot_ilot_refact' => ['type' => Type::string()],


                // dernier location
                'dernierlocation' => ['type' => Type::string()],

                // daterenouvellement de son contrat en cours
                'daterenouvellement' => ['type' => Type::string()],

                // location details
                'location_details' => ['type' => Type::string()],
                'location_detailsavenenant' => ['type' => Type::string()],

                //New
                'documentappartements' => ['type' => Type::listOf(GraphQL::type('Documentappartement')), 'description' => ''],
                //type vente
                'typevente' => ['type' => Type::int(), 'description' => ''],
                'typevente_Text' => ['type' => Type::string()],

                'montantvilla' => ['type' => Type::string()],

                //prix villa
                'prixappartement' => ['type' => Type::string()],




                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

    // dernierlocation

    protected function resolveDernierlocationField($root, $args)
    {
        if ($root->etatappartement && $root->etatappartement->designation == "Libre") {
            $contrat = $root->contrats->last();
            return    $contrat ? $contrat->datedebutcontrat : 'jamais loué';
        }
        return null;
    }

    // daterenouvellement

    protected function resolveDaterenouvellementField($root, $args)
    {
        if ($root->etatappartement && $root->etatappartement->designation == "Libre") {
            return "pas en location";
        }

        $contrat = $root->contrats->last();
        if ($contrat) {
            return $contrat->daterenouvellement;
        }
        return null;
    }

    protected function resolveLocationDetailsField($root, $args)
    {
        $avenant = $this->getAvenantActive($root['contrat_id']);
        if ($avenant) {
            return "Echeance : ". $avenant->dateecheance;
        }

        if ($root->etatappartement && $root->etatappartement->designation === "Libre") {
            $contrat = $root->contrats->last();
            if ($contrat && $contrat->dateecheance) {
                return "Dernière location : " . $contrat->dateecheance;
            }
            return "Jamais loué";
        }
        $contrat = $root->contrats->last();
        return $contrat ? "Echeance : ". $contrat->dateecheance : null;
    }
    private function getAvenantActive($idContrat) {
        $existing = Avenant::where([["contrat_id" , $idContrat],["est_activer" , 2]])->first();
        return ($existing) ? $existing :  null;
    }



    // tvamontant resolve

    protected function resolveTlvmontantField($root, $args)
    {
        $proprietaireId = $root['proprietaire_id'];
        $montantloyer = Outil::getTotalMontantPourTaxe('tlv', $proprietaireId);
        $valeurtlv = Outil::getValeurTaxe('TLV');
        $sommeDesTlv = $montantloyer * ($valeurtlv / 100);
        return $sommeDesTlv;
    }

    protected function resolveTvamountantField($root, $args)
    {
        $montant = Outil::getTotalMontantPourTaxe('tva', $root['proprietaire_id']);
        //dd($montant);
        $taxe = Outil::getValeurTaxe('tva');
        // dd($taxe);
        if (!$taxe) {
            return 0;
        }
        $tvamountant = $montant * $taxe / 100;

        // dd($tvamountant);
        return $tvamountant;
    }




    protected function resolveAcomptePercentField($root, $args)
    {
        $prixvilla = intval($root['prixvilla']);
        $acompte = intval($root['acomptevilla']);

        if ($acompte === 0) {
            return 0; // To avoid division by zero
        }

        $percentage = round(($acompte / $prixvilla) * 100, 2);

        return $percentage;
    }
    protected function resolveLotIlotRefactField($root, $args)
    {
        $lot = $root['lot'];
        $ilot = Ilot::find($root['ilot_id']);


        if (isset($ilot) && isset($ilot->id)) {
            $text = "Ilot : $ilot->numero / lot : $lot / " . $ilot->adresse;
            return $text;
        }


        return null;
    }
    protected function resolvePrixvillaformatField($root, $args)
    {
        $valeur_ht_format = Outil::formatPrixToMonetaire($root['prixvilla']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }
    protected function resolveAcompteFormatField($root, $args)
    {
        $valeur_ht_format = Outil::formatPrixToMonetaire($root['acomptevilla']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }
    protected function resolveTypeventeTextField($root, $args)
    {
        $typevente = $root['typevente'];
        $text = "Non defini";

        if($typevente){
            if ($typevente == 1) {
                $text = "Vente";
            } elseif ($typevente == 2) {
                $text = "Location";
            }
        }

        return $text;
    }
}
