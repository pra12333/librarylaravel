<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateAdminsToUsers extends Command
{
    protected $signature = 'migrate:admins-to-users';

    protected $description = 'Migrate admin data to users table';

    public function handle()
    {
        $admins = DB::table('admins')->get();

        foreach ($admins as $admin) {
            DB::table('users')->insert([
                'name' => $admin->name,
                'email' => $admin->email,
                'password' => $admin->password, // Assuming passwords are hashed
                'role' => $admin->is_super ? 'superadmin' : 'admin',
                'created_at' => $admin->created_at,
                'updated_at' => $admin->updated_at,
            ]);
        }

        $this->info('Admin data migrated successfully!');
    }
}