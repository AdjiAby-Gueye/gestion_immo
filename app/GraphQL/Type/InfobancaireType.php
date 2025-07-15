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

class InfobancaireType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Infobancaire',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id'                        => ['type' => Type::id(), 'description' => ''],
                'banque'                    => ['type' => Type::string(), 'description' => ''],
                'agence'                     => ['type' => Type::string()],
                'codebanque'                => ['type' => Type::string()],
                'codeguichet'               => ['type' => Type::string()],
                'clerib'                    => ['type' => Type::string()],
                'datedebut'                 => ['type' => Type::string()],
                'numerocompte'               => ['type' => Type::string()],
                'datefin'                   => ['type' => Type::string()],
                'entite_id'                 => ['type' => Type::int()],
                'entite'                    => ['type' => GraphQL::type('Entite')],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

    protected function resolveDatedebutField($root, $args)
    {
        return Outil::resolveAllDateCompletFR($root['datedebut'],false);
    }

    protected function resolveDatefinField($root, $args)
    {
        return Outil::resolveAllDateCompletFR($root['datefin'],false);
    }

}

