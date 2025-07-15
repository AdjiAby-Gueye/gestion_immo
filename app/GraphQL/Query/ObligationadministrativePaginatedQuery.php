<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class ObligationadministrativePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'obligationadministrativespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('obligationadministrativespaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'designation' => ['type' => Type::string()],
                'debut' => ['type' => Type::string()],
                'fin' => ['type' => Type::string()],
                'montant' => ['type' => Type::string()],
                'document' => ['type' => Type::string()],
                'typeobligationadministrative_id' => ['type' => Type::int()],
                'immeuble_id' => ['type' => Type::int()],
                'appartement_id' => ['type' => Type::int()],
                'typeobligationadministrative' => ['type' =>  GraphQL::type('Typeobligationadministrative')],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],


                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryObligationadministrative($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
