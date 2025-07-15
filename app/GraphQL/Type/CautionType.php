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

class CautionType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Caution',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'document' => ['type' => Type::string(), 'description' => ''],
                'montantloyer' => ['type' => Type::string(), 'description' => ''],
                'montantcaution' => ['type' => Type::string(), 'description' => ''],
                'codeappartement' => ['type' => Type::string(), 'description' => ''],
                'dateversement' => ['type' => Type::string(), 'description' => ''],
                'datepaiement' => ['type' => Type::string(), 'description' => ''],
                'etat' => ['type' => Type::string(), 'description' => ''],
                'contrat_id' => ['type' => Type::string(), 'description' => ''],
                'contrat' => ['type' =>  GraphQL::type('Contrat')],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

}

