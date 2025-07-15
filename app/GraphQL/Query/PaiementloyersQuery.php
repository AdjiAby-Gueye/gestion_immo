<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class PaiementloyersQuery extends Query
{
    protected $attributes = [
        'name' => 'paiementloyers',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Paiementloyer'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'datepaiement' => ['type' => Type::string()],
                'codefacture' => ['type' => Type::string()],
                'numero_cheque' => ['type' => Type::string(), 'description' => ''],

                // ajout recent
                'facturelocation_id' => ['type' => Type::int()],
                'montantfacture' => ['type' => Type::string()],
                'debutperiodevalide' => ['type' => Type::string()],
                'finperiodevalide' => ['type' => Type::string()],
                'periode' => ['type' => Type::string()],
                'contrat_id' => ['type' => Type::int()],
                'appartement_id' =>  ['type' => Type::int()],
                'locataire_id' => ['type' => Type::int()],
                'modepaiement_id' => ['type' => Type::int()],
                'factureeaux_id' => ['type'=> Type::int()],

                'motif_annulation_paiement' => ['type' => Type::string()],
                'date_annulation_paiement' => ['type' => Type::string()],
                'date_reactivation_paiement' => ['type' => Type::string()],
                'justificatif_paiement' => ['type' => Type::string()],

                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryPaiementloyer($args);
        return $query->get();

    }
}
