<?php

namespace Database\Seeders;

use App\Enums\Auth\RolesEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = RolesEnum::getValues();

        foreach ($roles as $key => $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
