<?php

namespace App\GraphQL\Type;

use App\Employe;
use App\Entite;
use App\RefactoringItems\RefactGraphQLType;


use App\Outil;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use Psy\Util\Str;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UserType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'User',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'name' => ['type' => Type::string(), 'description' => ''],
                'email' => ['type' => Type::string(), 'description' => ''],
                'password' => ['type' => Type::string(), 'description' => ''],
                'matricule' => ['type' => Type::string(), 'description' => ''],
                'image' => ['type' => Type::string(), 'description' => ''],
                'uploadsignature' => ['type' => Type::string() ],
                'locataire_id' => ['type' => Type::int()],
                'locataire' => ['type' =>  GraphQL::type('Locataire')],
                'entite_id' => ['type' => Type::int()],
                'entite' => ['type' =>  GraphQL::type('Entite')],
                'roles' => ['type' => Type::listOf(GraphQL::type('Role')), 'description' => ''],
                'entites' => ['type' => Type::listOf(GraphQL::type('Entite')), 'description' => ''],
                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

    /*protected function resolveUserDepartementsField($root, $args)
    {
        $query = DB::table('user_departements')
            ->join('users','users.id','=','user_departements.user_id')
            ->join('departements','departements.id','=','user_departements.departement_id')
            ->where('users.id', $root['id'])
            ->selectRaw('user_departements.*')
            ->get();

        return $query;
    }*/

    /*************** Pour les dates ***************/
    /*  protected function resolveCreatedAtField($root, $args)
      {
          if (!isset($root['created_at']))
          {
              $date_at = $root->created_at;
          }
          else
          {
              $date_at = is_string($root['created_at']) ? $root['created_at'] : $root['created_at']->format(Outil::formatdate());
          }
          return $date_at;
      }

      protected function resolveCreatedAtFrField($root, $args)
      {
          if (!isset($root['created_at']))
          {
              $created_at = $root->created_at;
          }
          else
          {
              $created_at = $root['created_at'];
          }
          if (!isset($created_at))
              return null;
          return Carbon::parse($created_at)->format('d/m/Y H:i:s');
      }

      protected function resolveUpdatedAtField($root, $args)
      {
          if (!isset($root['updated_at']))
          {
              $date_at = $root->updated_at;
          }
          else
          {
              $date_at = is_string($root['updated_at']) ? $root['updated_at'] : $root['updated_at']->format(Outil::formatdate());
          }
          return $date_at;
      }

      protected function resolveUpdatedAtFrField($root, $args)
      {
          if (!isset($root['created_at']))
          {
              $date_at = $root->created_at;
          }
          else
          {
              $date_at = $root['created_at'];
          }
          if (!isset($date_at))
              return null;
          return Carbon::parse($date_at)->format('d/m/Y H:i:s');
      }

      protected function resolveDeletedAtField($root, $args)
      {
          if (isset($root['deleted_at']))
          {
              $date_at = $root->updated_at;
          }
          else
          {
              $date_at = is_string($root['deleted_at']) ? $root['deleted_at'] : $root['deleted_at']->format(Outil::formatdate());
          }
          return $date_at;
      }


      protected function resolveDeletedAtFrField($root, $args)
      {
          if (isset($root['deleted_at']))
          {
              $date_at = $root->created_at;
          }
          else
          {
              $date_at = $root['deleted_at'];
          }
          if (!isset($date_at))
              return null;
          return Carbon::parse($date_at)->format('d/m/Y H:i:s');
      }
      /*************** /Pour les dates ***************/


    /* protected function resolveImageField($root, $args)
     {
         if (isset($root->image))
         {
             $image = $root->image;
         }
         else
         {
             $image = $root['image'];
         }

         if($image == null)
         {
             return 'assets/images/default.png';
         }
         else
         {
             return $image;
         }
     }*/

}

