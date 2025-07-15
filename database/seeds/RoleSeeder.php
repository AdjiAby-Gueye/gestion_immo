<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Assurer le rôle 'resident'
        $roleResident = Role::firstOrCreate(['name' => 'resident']);

        // Rôles à créer
        $roles = [
            [
                "name" => 'super-admin',
                "is_director" => 0,
            ],
            [
                "name" => 'director',
                "is_director" => 1,
            ]
        ];

        foreach ($roles as $value) {
            $role = Role::firstOrCreate(
                ['name' => $value['name']],
                ['is_director' => $value['is_director']]
            );

            if ($value['name'] === 'super-admin') {
                // Donner toutes les permissions au super-admin
                $role->syncPermissions(Permission::all());
            } else {
                // Exemple : exclure une permission spécifique pour les autres rôles
                $permissions = Permission::where('name', '!=', 'voir-depense-tranche-horaire')->get();
                $role->syncPermissions($permissions);
            }
        }
    }
}
