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

class CompteclientType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Compteclient',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],

                //locataire_id
                'locataire' => ['type' =>  GraphQL::type('Locataire')],
                'locataire_id' => ['type' => Type::int(), 'description' => ''],
                // montant
                'montant' => ['type' => Type::string(), 'description' => ''],
                // date
                'date' => ['type' => Type::string(), 'description' => ''],

                'etat' => ['type' => Type::int(), 'description' => ''],

                'user_id' => ['type' => Type::int(), 'description' => ''],


                'paiementecheance_id' => ['type' => Type::int(), 'description' => ''],

                // typetransaction  type int

                'typetransaction' => ['type' => Type::int() ,'description' => '' ],



                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }


}
