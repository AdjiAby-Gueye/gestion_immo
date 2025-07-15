<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class HistoriquerelancesQuery extends Query
{
    protected $attributes = [
        'name' => 'historiquerelances',
        'description' => ''
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Historiquerelance'));
    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'date_envoie' => ['type' => Type::string(), 'description' => ''],

                'contrat_id' => ['type' => Type::string(), 'description' => ''],
                'locataire_id' => ['type' => Type::string(), 'description' => ''],
                'user_id' => ['type' => Type::string(), 'description' => ''],
                'inbox_id' => ['type' => Type::string(), 'description' => ''],
                'avisecheance_id' => ['type' => Type::int(), 'description' => ''],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryHistoriqueRelance($args);
        return $query->get();

    }
}
