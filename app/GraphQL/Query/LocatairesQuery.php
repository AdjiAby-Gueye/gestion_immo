<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class LocatairesQuery extends Query
{
    protected $attributes = [
        'name' => 'locataires',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Locataire'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'prenom' => ['type' => Type::string()],
                'nom' => ['type' => Type::string()],
                'adresse' => ['type' => Type::string()],
                'telephoneportable1' => ['type' => Type::string()],
                'telephoneportable2' => ['type' => Type::string()],
                'telephonebureau' => ['type' => Type::string()],
                'email' => ['type' => Type::string()],
                'profession' => ['type' => Type::string()],
                'age' => ['type' => Type::string()],
                'cni' => ['type' => Type::string()],
                'passeport' => ['type' => Type::string()],
                'nomentreprise' => ['type' => Type::string()],
                'adresseentreprise' => ['type' => Type::string()],
                'ninea' => ['type' => Type::string()],
                'documentninea' => ['type' => Type::string()],
                'numerorg' => ['type' => Type::string()],
                'documentnumerorg' => ['type' => Type::string()],
                'documentstatut' => ['type' => Type::string()],
                'personnehabiliteasigner' => ['type' => Type::string()],
                'fonctionpersonnehabilite' => ['type' => Type::string()],
                'nompersonneacontacter' => ['type' => Type::string()],
                'prenompersonneacontacter' => ['type' => Type::string()],
                'emailpersonneacontacter' => ['type' => Type::string()],
                'telephone1personneacontacter' => ['type' => Type::string()],
                'telephone2personneacontacter' => ['type' => Type::string()],
                'etatlocataire' => ['type' => Type::string()],

                'numeroclient' => ['type' => Type::string()],

                'entite_id' => ['type' => Type::int()],
                'secteuractivite_id' => ['type' => Type::int()],
                'secteuractivite_id' => ['type' => Type::int()],

                'date_naissance' => ['type' => Type::string()],
                'lieux_naissance' => ['type' => Type::string()],
                'pays_naissance' => ['type' => Type::string()],
                'mandataire' => ['type' => Type::string()],

                'ville' => ['type' => Type::string()],
                'situationfamiliale' => ['type' => Type::string()],
                'codepostal' => ['type' => Type::string()],
                'nationalite' => ['type' => Type::string()],
                'njf' => ['type' => Type::string()],

                'revenus' => ['type' => Type::string()],
                'contrattravail' => ['type' => Type::string()],
                'typelocataire' => ['type' =>  GraphQL::type('Typelocataire')],
                'expatlocale' => ['type' => Type::string()],
                'nomcompletpersonnepriseencharge' => ['type' => Type::string()],
                'telephonepersonnepriseencharge' => ['type' => Type::string()],

                'typelocataire_id' => ['type' => Type::int()],
                'observation_id' => ['type' => Type::int()],
                'user_id' => ['type' => Type::int()],
                'user' => ['type' =>  GraphQL::type('User')],

                'search'          => ['type' => Type::string()],


                'appartements' => ['type' => Type::listOf(GraphQL::type('Appartement'))],
                'contrats' => ['type' => Type::listOf(GraphQL::type('Contrat'))],
                'interventions' => ['type' => Type::listOf(GraphQL::type('Intervention'))],
                'messages' => ['type' => Type::listOf(GraphQL::type('Message'))],
                'questionnairesatisfactions' => ['type' => Type::listOf(GraphQL::type('Questionnairesatisfaction'))],

                'est_copreuneur' => ['type' => Type::int()],

                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryLocataire($args);
        return $query->get();

    }
}
