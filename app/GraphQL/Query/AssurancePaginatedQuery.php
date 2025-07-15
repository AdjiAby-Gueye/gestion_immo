<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class AssurancePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'assurancespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('assurancespaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'descriptif' => ['type' => Type::string()],
                'montant' => ['type' => Type::string()],
                'debut' => ['type' => Type::string()],
                'fin' => ['type' => Type::string()],
                'document' => ['type' => Type::string()],
                'assureur_id' => ['type' => Type::int()],
                'etatassurance_id' => ['type' => Type::int()],
                'contrat_id' => ['type' => Type::int()],
                'assureur' => ['type' =>  GraphQL::type('Assureur')],
                'typeassurance' => ['type' =>  GraphQL::type('Typeassurance')],
                'prestataire' => ['type' =>  GraphQL::type('Prestataire')],
                'etatassurance' => ['type' =>  GraphQL::type('Etatassurance')],
                'contrat' => ['type' =>  GraphQL::type('Contrat')],


                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryAssurance($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
