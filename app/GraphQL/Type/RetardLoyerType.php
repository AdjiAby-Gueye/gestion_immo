<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class RetardLoyerType extends GraphQLType
{
    protected $attributes = [
        'name' => 'RetardLoyer',
        'description' => 'Nombre de retards de loyer pour un mois spécifique',
    ];

    public function fields(): array
    {
        return [
            'mois' => [
                'type' => Type::string(),
                'description' => 'Le mois (format YYYY-MM)',
            ],
            'nombre_de_retards' => [
                'type' => Type::int(),
                'description' => 'Le nombre de retards de loyer pour ce mois',
            ],
            'nombre_payer_a_temps' => [
                'type' => Type::int(),
                'description' => 'Le nombre de retards de loyer pour ce mois',
            ],

            'total' => [
                'type' => Type::float(),
                'description' => 'Le nombre total de locataires ayant payé leur loyer'
            ],
            'totalecheance' => [
                'type' => Type::float(),
                'description' => 'Le nombre total de locataires ayant payé leur loyer'
            ],



            // 

        ];
    }
}
