<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AssignSuperAdmin extends Command
{
    protected $signature = 'user:make-super-admin {email}';

    protected $description = 'Assign super_admin role to a user';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }

        if (!$user->hasRole('super_admin')) {
            $user->assignRole('super_admin');
            $this->info("✓ User '{$user->name}' is now a super admin!");
        } else {
            $this->warn("User '{$user->name}' is already a super admin.");
        }

        return 0;
    }
}
