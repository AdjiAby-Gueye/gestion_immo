<?php

namespace App\GraphQL\Type;

use App\Contrat;
use App\Paiementecheance;
use App\RefactoringItems\RefactGraphQLType;


use App\Outil;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use Psy\Util\Str;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class EtatencaissementType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Etatencaissement',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id'                            => ['type' => Type::id(), 'description' => ''],
                'locataire'                     => ['type' =>  GraphQL::type('Locataire')],
                'appartement'                   => ['type' =>  GraphQL::type('Appartement')],
                'etatencaissementdetail'        => ['type' =>  GraphQL::type('Etatencaissementdetail')],

                'nbpaiement'                    => ['type' => Type::int(), 'description' => ''],
                'email'                    => ['type' => Type::string(), 'description' => ''],
                'total'                         => ['type' => Type::float(), 'description' => ''],
            ];
    }

    protected function resolveEtatencaissementdetailField($root, $args)
    {
        return Outil::getfielMountdAvisecheance($root['id']);
    }
    protected function resolveCotepartammortissementField($root, $args)
    {
        return Outil::getfielMountdAvisecheance($root['id'])['totalAmortissement'];
    }

    protected function resolveFraislocatifField($root, $args)
    {
        return Outil::getfielMountdAvisecheance($root['id'])['totalFraislocatif'];
    }

    protected function resolveFraisgestionField($root, $args)
    {
        return Outil::getfielMountdAvisecheance($root['id'])['totalFraisgestion'];
    }
//    protected function resolveNbPaiementField($root, $args)
//    {
//        $paiementecheance = Paiementecheance::query()
//            ->get(['avisecheance_id']);
//
//        $query =  Contrat::query()
//            ->join("avisecheances", "avisecheances.contrat_id", "contrats.id")
////            ->where('avisecheances.periodes',  Outil::getOperateurLikeDB(), '%' . $periode . '%')
//            ->whereIn('avisecheances.id',$paiementecheance)
//            ->where('contrats.id',$root['id'])
//            ->groupBy(['contrats.id'])
//            ->selectRaw('contrats.*, count(avisecheances.id) as nbpaiement')->first();
//
//        return $query->nbpaiement;
//    }

}

