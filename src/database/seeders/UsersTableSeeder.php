<?php

namespace Database\Seeders;

use Carbon\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;

use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userRole = Role::where('name', 'user')->first();

        $users = User::factory()
        ->count(50)
        ->create()
        ->each(function ($user) {
            UserProfile::factory()->for($user)->create();
        });

        foreach ($users as $user) {
            $user->assignRole($userRole);
        }
    }
}
