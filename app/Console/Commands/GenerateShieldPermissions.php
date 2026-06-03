<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class GenerateShieldPermissions extends Command
{
    protected $signature = 'shield:setup';

    protected $description = 'Generate Shield permissions manually for server deployment';

    public function handle()
    {
        $this->info('Generating permissions...');

        // Define all resources and their permissions
        $resources = [
            'Amenity', 'Banner', 'Governorate', 'Compound', 'Contact', 'Developer',
            'Faq', 'Favorite', 'MaintenanceBooking', 'MaintenanceService',
            'Page', 'Review', 'Service', 'Testimonial', 'Transaction',
            'Unit', 'UnitMedia', 'UnitType', 'User', 'Viewing', 'Role'
        ];

        $actions = [
            'ViewAny', 'View', 'Create', 'Update', 'Delete',
            'Restore', 'ForceDelete', 'ForceDeleteAny', 'RestoreAny',
            'Replicate', 'Reorder'
        ];

        $count = 0;
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $permissionName = "{$action}:{$resource}";
                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web'
                ]);
                $count++;
            }
        }

        $this->info("✓ Created {$count} permissions");

        // Assign all permissions to super_admin
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->syncPermissions(Permission::all());
            $this->info('✓ Assigned all permissions to super_admin role');
        }

        $this->info('✓ Shield setup completed successfully!');
        return 0;
    }
}
