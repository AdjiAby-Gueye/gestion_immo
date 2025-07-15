<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\QueryModel;
use Illuminate\Support\Facades\Auth;
use \Spatie\Permission\Models\Permission;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class PermissionsQuery extends Query
{
    protected $attributes = [
        'name' => 'permissions'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Permission'));
    }

    public function args(): array
    {
        return
            [
                'id'                    => ['type' => Type::int()],
                'name'                  => ['type' => Type::string()],
                'display_name'          => ['type' => Type::string()],
                'activer'               => ['type' => Type::int()],
                'search'                => ['type' => Type::string()],
                'designation'           => ['type' => Type::string()],

            ];
    }
    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryPermission($args);
        return $query->get();

    }

}
