<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ContratsQuery extends Query
{
    protected $attributes = [
        'name' => 'contrats',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Contrat'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'est_soumis' => ['type' => Type::int(), 'description' => ''],
                'est_copreuneur' => ['type' => Type::int(), 'description' => ''],
                'copreneur_id' => ['type' => Type::int() ,  'description' => 'copreneur id'],
                'codeappartement' => ['type' => Type::string()],
                'retourcaution' => ['type' => Type::string()],
                'status' => ['type' => Type::string()],
                'document' => ['type' => Type::string()],
                'scanpreavis' => ['type' => Type::string()],
                'descriptif' => ['type' => Type::string()],
                'documentretourcaution' => ['type' => Type::string()],
                'documentrecucaution' => ['type' => Type::string()],
                'montantloyer' => ['type' => Type::string()],
                'montantloyerbase' => ['type' => Type::string()],
                'montantloyertom' => ['type' => Type::string()],
                'montantcharge' => ['type' => Type::string()],
                'tauxrevision' => ['type' => Type::string()],
                'frequencerevision' => ['type' => Type::string()],
                'dateenregistrement' => ['type' => Type::string()],
                'daterenouvellement' => ['type' => Type::string()],
                'datepremierpaiement' => ['type' => Type::string()],
                'dateretourcaution' => ['type' => Type::string()],
                'daterenouvellementcontrat' => ['type' => Type::string()],
                'datedebutcontrat' => ['type' => Type::string()],
                'etat' => ['type' => Type::int(), 'description' => ''],
                'rappelpaiement' => ['type' => Type::int(), 'description' => ''],
                'numerodossier' => ['type' => Type::string()],
                'facturelocation_id' => ['type' => Type::int(), 'description' => ''],
                'factureeaux_id' => ['type' => Type::int(), 'description' => ''],



                'typecontrat' => ['type' =>  GraphQL::type('Typecontrat')],
                'typerenouvellement' => ['type' =>  GraphQL::type('Typerenouvellement')],
                'delaipreavi' => ['type' =>  GraphQL::type('Delaipreavi')],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],
                'locataire' => ['type' =>  GraphQL::type('Locataire')],
                'caution' => ['type' =>  GraphQL::type('Caution')],

                 //new fileds
                 'dateremisecles' => ['type' => Type::string(), 'description' => ''],
                 'apportinitial' => ['type' => Type::string(), 'description' => ''],
                 'apportiponctuel' => ['type' => Type::string(), 'description' => ''],
                 'dateecheance' => ['type' => Type::string(), 'description' => ''],
                 'dureelocationvente' => ['type' => Type::int(), 'description' => ''],
                 'clausepenale' => ['type' => Type::string(), 'description' => ''],
                 'fraiscoutlocationvente' => ['type' => Type::string(), 'description' => ''],
                 'acompteinitial' => ['type' => Type::string(), 'description' => ''],
                 'prixvilla' => ['type' => Type::string(), 'description' => ''],
                 'indemnite' => ['type' => Type::int(), 'description' => ''],

                 'depot_initial' => ['type' => Type::string()],
                 'frais_gestion' => ['type' => Type::int()],

                 'signaturedirecteur' => ['type' => Type::string()],
                 'signatureclient' => ['type' => Type::string()],
                 'usersigned_id' => ['type' => Type::int()],


                'typecontrat_id' => ['type' => Type::int()],
                'typerenouvellement_id' => ['type' => Type::int()],
                'delaipreavi_id' => ['type' => Type::int()],
                'locataire_id' => ['type' => Type::int()],
                'appartement_id' => ['type' => Type::int()],
                'assurances' => ['type' => Type::listOf(GraphQL::type('Assurance'))],
                'versementloyers' => ['type' => Type::listOf(GraphQL::type('Versementloyer'))],
                'versementchargecoproprietes' => ['type' => Type::listOf(GraphQL::type('Versementchargecopropriete'))],
                'paiementloyers' => ['type' => Type::listOf(GraphQL::type('Paiementloyer'))],
                'demanderesiliations' => ['type' => Type::listOf(GraphQL::type('Demanderesiliation'))],
                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryContrat($args);
        return $query->get();

    }
}
