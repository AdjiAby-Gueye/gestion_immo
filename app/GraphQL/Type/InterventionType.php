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

class InterventionType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Intervention',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'descriptif' => ['type' => Type::string(), 'description' => ''],
                'etat' => ['type' => Type::string(), 'description' => ''],
                'image' => ['type' => Type::string(), 'description' => ''],
                'dateintervention' => ['type' => Type::string(), 'description' => ''],
                
                'datefinintervention' => ['type' => Type::string(), 'description' => ''],
                'categorieintervention' => ['type' =>  GraphQL::type('Categorieintervention')],
                'typeintervention' => ['type' =>  GraphQL::type('Typeintervention')],
                'demandeintervention' => ['type' =>  GraphQL::type('Demandeintervention')],
                'etatlieu' => ['type' =>  GraphQL::type('Etatlieu')],
                'prestataire' => ['type' =>  GraphQL::type('Prestataire')],
                'locataire' => ['type' =>  GraphQL::type('Locataire')],
                'facture' => ['type' =>  GraphQL::type('Facture')],
                'factureintervention_id' => ['type' => Type::string(), 'description' => ''],
                'factureintervention' => ['type' =>  GraphQL::type('Factureintervention')],
                'categorieintervention_id' => ['type' => Type::string(), 'description' => ''],
                'typeintervention_id' => ['type' => Type::string(), 'description' => ''],
                'demandeintervention_id' => ['type' => Type::string(), 'description' => ''],
                'etatlieu_id' => ['type' => Type::string(), 'description' => ''],
                'prestataire_id' => ['type' => Type::string(), 'description' => ''],
                'locataire_id' => ['type' => Type::string(), 'description' => ''],
                'facture_id' => ['type' => Type::string(), 'description' => ''],
                'rapportintervention' => ['type' =>  GraphQL::type('Rapportintervention')],
                'membreequipegestion' => ['type' =>  GraphQL::type('Membreequipegestion')],
                'questionnairesatisfactions' => ['type' => Type::listOf(GraphQL::type('Questionnairesatisfaction')), 'description' => ''],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }


}

