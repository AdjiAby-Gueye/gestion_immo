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

class CopreneurType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Copreneur',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'prenom' => ['type' => Type::string(), 'description' => ''],
                'nom' => ['type' => Type::string(), 'description' => ''],
                'adresse' => ['type' => Type::string(), 'description' => ''],
                'telephone1' => ['type' => Type::string(), 'description' => ''],
                'telephone2' => ['type' => Type::string(), 'description' => ''],
                'email' => ['type' => Type::string(), 'description' => ''],
                'profession' => ['type' => Type::string(), 'description' => ''],
                'cni' => ['type' => Type::string(), 'description' => ''],
                'passeport' => ['type' => Type::string(), 'description' => ''],

                'datenaissance' => ['type' => Type::string()],
                'datenaissance_format' => ['type' => Type::string()],
                'lieunaissance' => ['type' => Type::string()],
                'pays' => ['type' => Type::string()],

                'ville' => ['type' => Type::string()],
                'situationfamiliale' => ['type' => Type::string()],
                'codepostal' => ['type' => Type::string()],
                'nationalite' => ['type' => Type::string()],
                'njf' => ['type' => Type::string()],

                'locataire' => ['type' =>  GraphQL::type('Locataire')],
                'locataire_id' => ['type' => Type::int(), 'description' => ''],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

    protected function resolveDateNaissanceFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['datenaissance']);
    }

}

