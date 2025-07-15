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

class EtatappartementType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Etatappartement',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'designation' => ['type' => Type::string(), 'description' => ''],
                'appartements' => ['type' => Type::listOf(GraphQL::type('Appartement')), 'description' => ''],
                'etat_text' => ['type' => Type::string(), 'description' => ''],
                'etat_badge'=> ['type' => Type::string(), 'description' => ''],
                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

    protected function resolveEtatTextField($root, $args)
    {

        $itemArray = array("designation" => $root['designation']);
        $retour = Outil::donneEtatGeneral("appartement", $itemArray)['texte'];
        if (empty($retour)) {
            $retour = "";
        }
        return $retour;
    }

    protected function resolveEtatBadgeField($root, $args)
    {
        $itemArray = array("designation" => $root['designation']);
        $retour = Outil::donneEtatGeneral("appartement", $itemArray)['badge'];
        if (empty($retour)) {
            $retour = "";
        }
        return $retour;
    }

}

