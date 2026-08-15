<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::query()->firstOrCreate([
            'name' => 'panel_user',
            'guard_name' => 'web',
        ]);

        Role::query()->firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        Artisan::call('shield:generate', [
            '--all' => true,
            '--panel' => 'admin',
            '--no-interaction' => true,
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $superAdmin = Role::query()->where('name', 'super_admin')->where('guard_name', 'web')->firstOrFail();

        $superAdmin->permissions()->sync(
            Permission::query()
                ->where('guard_name', 'web')
                ->pluck('id')
                ->all()
        );
    }
}
