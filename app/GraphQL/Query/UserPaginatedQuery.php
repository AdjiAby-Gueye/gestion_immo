<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class UserPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'userspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('userspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::int()],
                'name' => ['type' => Type::string()],
                'email' => ['type' => Type::string()],
                'search' => ['type' => Type::string()],
                'uploadsignature' => ['type' => Type::string() ],
                'locataire_id' => ['type' => Type::int()],
                'locataire' => ['type' =>  GraphQL::type('Locataire')],
                'role_id' => ['type' => Type::int()],
                'entite_id' => ['type' => Type::int()],
                'entite' => ['type' =>  GraphQL::type('Entite')],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

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
        if (isset($args['search'])) {
            $query = $query->where('name', Outil::getOperateurLikeDB(), '%' . $args['search'] . '%')
                ->orWhere('email', Outil::getOperateurLikeDB(), '%' . $args['search'] . '%');
        }
        if (isset($args['entite_id'])) {
            $query = $query->where('entite_id', $args['entite_id']);
        }


        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
