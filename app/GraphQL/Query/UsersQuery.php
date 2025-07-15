<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\User;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UsersQuery extends Query
{
    protected $attributes = [
        'name' => 'users',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('User'));
    }

    // arguments to filter query
    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::int()],
                'name' => ['type' => Type::string()],
                'email' => ['type' => Type::string()],
                'search' => ['type' => Type::string()],
                'uploadsignature' => ['type' => Type::string() ],
                'role_id' => ['type' => Type::int()],
                'locataire_id' => ['type' => Type::int()],
                'locataire' => ['type' =>  GraphQL::type('Locataire')],
                'entite_id' => ['type' => Type::int()],
                'entite' => ['type' =>  GraphQL::type('Entite')],
                'order'                                     => ['type' => Type::string()],
                'direction'                                 => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = User::with('roles')->where('email', '!=', 'guindytechnology@gmail.com');

        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }
        if (isset($args['role_id'])) {
            $role_id = $args['role_id'];
            $query = $query->whereHas('roles', function ($query) use ($role_id) {
                $query->where('id', $role_id);
            });
        }
        if (isset($args['name'])) {
            $query = $query->where('name', Outil::getOperateurLikeDB(), '%' . $args['name'] . '%');
        }
        if (isset($args['email'])) {
            $query = $query->where('email', Outil::getOperateurLikeDB(), '%' . $args['email'] . '%');
        }
        if (isset($args['password'])) {
            $query = $query->where('password', Outil::getOperateurLikeDB(), '%' . $args['password'] . '%');
        }
        if (isset($args['search'])) {
            $query = $query->where('name', Outil::getOperateurLikeDB(), '%' . $args['search'] . '%')
                ->orWhere('email', Outil::getOperateurLikeDB(), '%' . $args['search'] . '%');
        }
        if (isset($args['entite_id'])) {
            $query = $query->where('entite_id', $args['entite_id']);
        }
        if (isset($args['locataire_id'])) {
            $query = $query->where('locataire_id', $args['locataire_id']);
        }
        if (isset($args['locataire'])) {
            $query = $query->where('locataire', $args['locataire']);
        }

        $query = $query->get();
        return  $query;

    }
}
