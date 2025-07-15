<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class AvenantsQuery extends Query
{
    protected $attributes = [
        'name' => 'avenants',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Avenant'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],

                'descriptif' => ['type' => Type::string(), 'description' => ''],
              
                'montantloyer' => ['type' => Type::string(), 'description' => ''],
                'montantloyerbase' => ['type' => Type::string(), 'description' => ''],
                'montantloyertom' => ['type' => Type::string(), 'description' => ''],
                'montantcharge' => ['type' => Type::string(), 'description' => ''],
                'tauxrevision' => ['type' => Type::string(), 'description' => ''],
                'frequencerevision' => ['type' => Type::string(), 'description' => ''],
                'dateenregistrement' => ['type' => Type::string(), 'description' => ''],
                'daterenouvellement' => ['type' => Type::string(), 'description' => ''],
                'rappelpaiement' => ['type' => Type::int(), 'description' => ''],
                'dateretourcaution' => ['type' => Type::string(), 'description' => ''],
                'datedebutcontrat' => ['type' => Type::string(), 'description' => ''],
                'est_activer' => ['type' => Type::int(), 'description' => ''],
                
                'typecontrat_id' => ['type' => Type::string(), 'description' => ''],
                'contrat_id' => ['type' => Type::int(), 'description' => ''],
                'typerenouvellement_id' => ['type' => Type::string(), 'description' => ''],
                'delaipreavi_id' => ['type' => Type::string(), 'description' => ''],
                'appartement_id' => ['type' => Type::int()],
                'locataire_id' => ['type' => Type::int()],
               
                'dateecheance' => ['type' => Type::string(), 'description' => ''],
                
                'total_loyer' => ['type' => Type::string()],
                             

                'periodicite_id' => ['type' => Type::int()],
                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryAvenant($args);
        return $query->get();

    }
}
