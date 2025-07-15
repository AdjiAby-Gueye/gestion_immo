<?php

use App\Outil;
use Illuminate\Database\Seeder;
use App\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                "name" => "root",
                "email" => "adjiaby.gueye@edu.unchk.sn",
                "image" => 'assets/images/upload.jpg',
                "password" => "passer123",
                "role" => "super-admin",
                "last_login_ip" => "127.0.0.1",
                "activer" => 1
            ]
        ];

        foreach ($users as $userData) {
            // Rechercher même ceux supprimés (soft delete)
            $user = User::withTrashed()->where('email', $userData['email'])->first();

            if (!$user) {
                // Nouveau user
                $user = new User();
                $user->email = $userData['email'];
            }

            // Remplir les champs
            $user->name = $userData['name'];
            $user->image = $userData['image'];
            $user->password = bcrypt($userData['password']);
            $user->active = $userData['activer'];
            $user->last_login_ip = $userData['last_login_ip'];

            $user->save();

            // Rôle
            $role = Role::firstOrCreate(['name' => $userData['role']]);
            $user->syncRoles([$role]);

            // Sauvegarder le matricule
            Outil::saveMatriculeUser($user);
        }
    }
}
