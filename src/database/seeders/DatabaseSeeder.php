<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 一般ユーザ・管理者のロール作成
        Role::create(['name' => 'user']);
        $adminRole = Role::create(['name' => 'admin']);
        $registerPermission = Permission::create(['name' => 'delete account']);

        $this->call([
            ConditionsTableSeeder::class,
            CategoriesTableSeeder::class,
            UsersTableSeeder::class,
            ItemsTableSeeder::class,
            CommentTableSeeder::class,
            FavoriteTableSeeder::class,
        ]);

        // 管理者を追加
        $admin = User::create([
            //必要に応じて変更してください。
            'id' => 0,
            'email' => 'adminuser@testuser.com',
            'password' => bcrypt(env('ADMIN_PASSWORD')),
        ]);

        $adminRole->givePermissionTo($registerPermission);
        $admin->assignRole($adminRole);
    }
}
