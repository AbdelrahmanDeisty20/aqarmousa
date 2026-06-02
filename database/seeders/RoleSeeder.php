<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 0. Create super_admin role first (required by Shield)
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdminRole->givePermissionTo(Permission::all());

        // 1. Create Roles
        $roles = [
            'content_manager' => [
                'ViewAny:Page', 'View:Page', 'Update:Page', 'Create:Page',
                'ViewAny:Faq', 'View:Faq', 'Update:Faq', 'Create:Faq', 'Delete:Faq',
                'ViewAny:Banner', 'View:Banner', 'Update:Banner', 'Create:Banner', 'Delete:Banner',
                'ViewAny:Testimonial', 'View:Testimonial', 'Update:Testimonial', 'Create:Testimonial',
                'ViewAny:Service', 'View:Service', 'Update:Service',
            ],
            'sales_agent' => [
                'ViewAny:Unit', 'View:Unit', 'Update:Unit', 'Create:Unit',
                'ViewAny:UnitMedia', 'View:UnitMedia', 'Update:UnitMedia', 'Create:UnitMedia',
                'ViewAny:Compound', 'View:Compound', 'Update:Compound', 'Create:Compound',
                'ViewAny:Developer', 'View:Developer', 'Update:Developer',
                'ViewAny:Viewing', 'View:Viewing', 'Update:Viewing',
                'ViewAny:Contact', 'View:Contact', 'Update:Contact',
                'ViewAny:City', 'View:City',
            ],
            'financial_viewer' => [
                'ViewAny:Transaction', 'View:Transaction',
                'ViewAny:MaintenanceBooking', 'View:MaintenanceBooking',
            ],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

            // Filter only existing permissions to avoid errors
            $existingPermissions = Permission::whereIn('name', $permissions)->pluck('name')->toArray();
            $role->syncPermissions($existingPermissions);
        }
    }
}
