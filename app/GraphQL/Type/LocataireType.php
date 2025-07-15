<?php

namespace App\GraphQL\Type;

use App\RefactoringItems\RefactGraphQLType;


use App\Outil;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use Psy\Util\Str;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Compteclient;

class LocataireType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Locataire',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'prenom' => ['type' => Type::string(), 'description' => ''],
                'nom' => ['type' => Type::string(), 'description' => ''],
                'adresse' => ['type' => Type::string(), 'description' => ''],
                'telephoneportable1' => ['type' => Type::string(), 'description' => ''],
                'telephoneportable2' => ['type' => Type::string(), 'description' => ''],
                'telephonebureau' =>['type' => Type::string(), 'description' => ''],
                'email' => ['type' => Type::string(), 'description' => ''],
                'profession' => ['type' => Type::string(), 'description' => ''],
                'age' => ['type' => Type::string(), 'description' => ''],
                'cni' => ['type' => Type::string(), 'description' => ''],
                'passeport' => ['type' => Type::string(), 'description' => ''],
                'nomentreprise' => ['type' => Type::string(), 'description' => ''],
                'adresseentreprise' =>['type' => Type::string(), 'description' => ''],
                'ninea' => ['type' => Type::string(), 'description' => ''],
                'documentninea' => ['type' => Type::string(), 'description' => ''],
                'numerorg' => ['type' => Type::string(), 'description' => ''],
                'documentnumerorg' => ['type' => Type::string(), 'description' => ''],
                'documentstatut' => ['type' => Type::string(), 'description' => ''],
                'personnehabiliteasigner' => ['type' => Type::string(), 'description' => ''],
                'fonctionpersonnehabilite' => ['type' => Type::string(), 'description' => ''],
                'nompersonneacontacter' => ['type' => Type::string(), 'description' => ''],
                'prenompersonneacontacter' => ['type' => Type::string(), 'description' => ''],
                'emailpersonneacontacter' => ['type' => Type::string(), 'description' => ''],
                'telephone1personneacontacter' => ['type' => Type::string(), 'description' => ''],
                'telephone2personneacontacter' => ['type' => Type::string(), 'description' => ''],
                'etatlocataire' => ['type' => Type::string(), 'description' => ''],
                'revenus' => ['type' => Type::string(), 'description' => ''],
                'contrattravail' => ['type' => Type::string(), 'description' => ''],
                'expatlocale' => ['type' => Type::string(), 'description' => ''],
                'nomcompletpersonnepriseencharge' => ['type' => Type::string(), 'description' => ''],
                'telephonepersonnepriseencharge' => ['type' => Type::string(), 'description' => ''],
                'typelocataire' => ['type' =>  GraphQL::type('Typelocataire')],
                'typelocataire_id' => ['type' => Type::string(), 'description' => ''],
                'user_id' => ['type' => Type::int()],
                'user' => ['type' =>  GraphQL::type('User')],
                'observation_id' => ['type' => Type::string(), 'description' => ''],
                'appartements' => ['type' => Type::listOf(GraphQL::type('Appartement')), 'description' => ''],
                'contrats' => ['type' => Type::listOf(GraphQL::type('Contrat')), 'description' => ''],
                // 'messages' => ['type' => Type::listOf(GraphQL::type('Message')), 'description' => ''],
                // 'questionnairesatisfactions' => ['type' => Type::listOf(GraphQL::type('Questionnairesatisfaction')), 'description' => ''],

                'est_copreuneur' => ['type' => Type::int()],
                'numeroclient' => ['type' => Type::string()],

                'entite_id' => ['type' => Type::int()],
                'entite' => ['type' =>  GraphQL::type('Entite')],


                'factureintervention_id' => ['type' => Type::int()],
             //   'factureintervention' => ['type' =>  GraphQL::type('Factureintervention')],
                'factureinterventions' => ['type' => Type::listOf(GraphQL::type('Factureintervention')), 'description' => ''],

                'secteuractivite_id' => ['type' => Type::int()],
                'secteuractivite' => ['type' =>  GraphQL::type('Secteuractivite')],

                'date_naissance' => ['type' => Type::string()],
                'date_naissance_format' => ['type' => Type::string()],
                'lieux_naissance' => ['type' => Type::string()],
                'pays_naissance' => ['type' => Type::string()],
                'mandataire' => ['type' => Type::string()],

                 // soldeclient
                 'soldeclient' => ['type' => Type::string(), 'description' => ''],
                 'soldeclient_format' => ['type' => Type::string(), 'description' => ''],


                'ville' => ['type' => Type::string()],
                'situationfamiliale' => ['type' => Type::string()],
                'codepostal' => ['type' => Type::string()],
                'nationalite' => ['type' => Type::string()],
                'njf' => ['type' => Type::string()],

                'copreneurs' => ['type' =>  Type::listOf(GraphQL::type('Copreneur'))],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

    protected function resolveDateNaissanceFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['date_naissance']);
    }

    protected function resolveSoldeclientField($root, $args)
    {
        $solde = 0;
        if (isset($root['id']) && !empty($root['id'])) {
            $montants = Compteclient::where('locataire_id', $root['id'])
            ->whereNull('etat')
            ->orderBy('created_at', 'desc')
            ->pluck('montant');

            foreach ($montants as $montant) {
                $solde += is_numeric($montant) ? intval($montant) : 0;
            }
        }

        return $solde;

    }
    protected function resolveSoldeclientFormatField($root, $args)
    {
        $total = $this->resolveSoldeclientField($root, $args);
        $valeur_ht_format = Outil::formatPrixToMonetaire($total);

        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }
        if($total == 0){
            return $total;
        }

        return $valeur_ht_format;
    }

}

