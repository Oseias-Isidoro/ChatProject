<?php

namespace Database\Seeders;

use App\Enums\UserRolesEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (UserRolesEnum::all() as $role) {
            Role::create(['name' => $role]);
        }
    }
}
