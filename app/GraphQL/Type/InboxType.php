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

class InboxType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Inbox',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'subject' => ['type' => Type::string(), 'description' => ''],
                'body' => ['type' => Type::string()],
                'sender_email' => ['type' => Type::string()],
                'user_id' => ['type' => Type::int()],
                'user' => ['type' => GraphQL::type('User')],

                'locataire_id' => ['type' => Type::int()],
                'locataire' => ['type' => GraphQL::type('Locataire')],
                'appartement_id' => ['type' => Type::int()],
                'appartement' => ['type' => GraphQL::type('Appartement')],

                'attachements' => ['type' => Type::listOf(GraphQL::type('Attachement')), 'description' => ''],
                'heure_envoie' => ['type' => Type::string(), 'description' => ''],
                
                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }
    protected function resolveHeureEnvoieField($root) {
      
        $date = strtotime($root['created_at']);
        $current = strtotime(date('Y-m-d H:i:s'));
       
        $hour= "";
        if (date('Y-m-d', $date) == date('Y-m-d', $current)) {
            // Si la date est aujourd'hui, affiche l'heure
            $hour = date('H:i', $date);
        } else {
            // Sinon, affiche le jour et le mois en texte
            $hour = date('d F', $date);
        }
        return $hour;

    }

}

