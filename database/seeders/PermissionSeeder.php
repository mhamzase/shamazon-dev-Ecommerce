<?php

namespace Database\Seeders;

use App\Enums\UserType;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['guard_name' => 'web', 'name' => 'view-products', 'module' => 'Products'],
            ['guard_name' => 'web', 'name' => 'create-products', 'module' => 'Products'],
            ['guard_name' => 'web', 'name' => 'edit-products', 'module' => 'Products'],
            ['guard_name' => 'web', 'name' => 'delete-products', 'module' => 'Products'],
        ];

        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission['name'])->where('guard_name', $permission['guard_name'])->first()) {
                Permission::create($permission);
            }
        }

        // create vendor role
        $role = Role::where(['name' => 'Vendor', 'guard_name' => 'web'])->first();
        if (!$role) {
            $role = Role::create([
                'name' => UserType::getTypeName(UserType::VENDOR),
                'guard_name' => 'web'
            ]);
        }

        // create buyer role
        $role = Role::where(['name' => 'Buyer', 'guard_name' => 'web'])->first();
        if (!$role) {
            $role = Role::create([
                'name' => UserType::getTypeName(UserType::BUYER),
                'guard_name' => 'web'
            ]);
        }
    }
}
