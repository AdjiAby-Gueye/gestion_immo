<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ContratprestationsQuery extends Query
{
    protected $attributes = [
        'name' => 'contratprestations',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Contratprestation'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'datesignaturecontrat' => ['type' => Type::string()],
                'datedemarragecontrat' => ['type' => Type::string()],
                'daterenouvellementcontrat' => ['type' => Type::string()],
                'datepremiereprestation' => ['type' => Type::string()],
                'document' => ['type' => Type::string()],
                'montant' => ['type' => Type::string()],
                'datepremierefacture' => ['type' => Type::string()],
                'frequencepaiementappartement' => ['type' =>  GraphQL::type('Frequencepaiementappartement')],
                'categorieprestation' => ['type' =>  GraphQL::type('Categorieprestation')],
                'prestataire' => ['type' =>  GraphQL::type('Prestataire')],

                'documents' => ['type' => Type::listOf(GraphQL::type('Document'))],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryContratprestation($args);
        return $query->get();

    }
}
