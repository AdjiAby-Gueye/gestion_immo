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

class DemanderesiliationType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Demanderesiliation',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'datedebutcontrat' => ['type' => Type::string(), 'description' => ''],
                'datedebutcontrat_format' => ['type' => Type::string(), 'description' => ''],
                'etat' => ['type' => Type::int(), 'description' => ''],
                'retourcaution' => ['type' => Type::int(), 'description' => ''],
                'etat_text' => ['type' => Type::string()],
                'etat_badge'=> ['type' => Type::string()],
                'datedemande' => ['type' => Type::string(), 'description' => ''],
                'datedemande_format' => ['type' => Type::string(), 'description' => ''],
                'delaipreavisrespecte' => ['type' => Type::string(), 'description' => ''],
                'raisonnonrespectdelai' => ['type' => Type::string(), 'description' => ''],
                'delaipreavi' => ['type' =>  GraphQL::type('Delaipreavi')],
                'dateeffectivite' => ['type' => Type::string(), 'description' => ''],
                'dateeffectivite_format' => ['type' => Type::string(), 'description' => ''],
                'contrat' => ['type' =>  GraphQL::type('Contrat')],
                'contrat_id' => ['type' => Type::int(), 'description' => ''],
                'document' => ['type' => Type::string(), 'description' => ''],
                'motif' => ['type' => Type::string(), 'description' => ''],

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

        $itemArray = array("etat" => $root['etat']);
        $retour = Outil::donneEtatGeneral("demanderesiliation", $itemArray)['texte'];
        if (empty($retour)) {
            $retour = "";
        }
        return $retour;
    }

    protected function resolveEtatBadgeField($root, $args)
    {

        $itemArray = array("etat" => $root['etat']);
        $retour = Outil::donneEtatGeneral("demanderesiliation", $itemArray)['badge'];
        if (empty($retour)) {
            $retour = "";
        }
        return $retour;
    }

    protected function resolveDatedebutcontratFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['datedebutcontrat']);
    }

    protected function resolveDatedemandeFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['datedemande']);
    }

    protected function resolveDateeffectiviteFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['dateeffectivite']);
    }
}

