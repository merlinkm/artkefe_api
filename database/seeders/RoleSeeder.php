<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['role' => 'admin'],
            ['role' => 'seller'],
            ['role' => 'user'],
        ];

        foreach($roles as $role){
            Role::create($role);
        }
    }
}
