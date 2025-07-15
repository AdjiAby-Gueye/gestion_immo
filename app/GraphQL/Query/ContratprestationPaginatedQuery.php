<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class ContratprestationPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'contratprestationspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('contratprestationspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'datesignaturecontrat' => ['type' => Type::string()],
                'datedemarragecontrat' => ['type' => Type::string()],
                'daterenouvellementcontrat' => ['type' => Type::string()],
                'datepremiereprestation' => ['type' => Type::string()],
                'datepremierefacture' => ['type' => Type::string()],
                'document' => ['type' => Type::string()],
                'montant' => ['type' => Type::string()],
                'frequencepaiementappartement' => ['type' =>  GraphQL::type('Frequencepaiementappartement')],
                'categorieprestation' => ['type' =>  GraphQL::type('Categorieprestation')],
                'prestataire' => ['type' =>  GraphQL::type('Prestataire')],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryContratprestation($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
