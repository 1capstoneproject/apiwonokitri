<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models;
use Illuminate\Support\Facades\Hash;

class Initialize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:initialize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize App Data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // count role
        $cr = Models\Role::count();
        if(!$cr){
            // create roles
            Models\Role::create([
                    'name' => 'base.role_superadmin',
                    'description' => 'Superadmin Roles',    
            ]);
            Models\Role::create([
                    'name' => 'base.role_admin',
                    'description' => 'Admin Role',      
            ]);
            Models\Role::create([
                'name' => 'base.role_users',
                'description' => 'Users Role',  
            ]);
            $this->info("Success create role.");
        }

        // create superusers when users not exist
        $cu = Models\User::count();
        if(!$cu){
            $role = Models\Role::where('name', 'base.role_superadmin')->first();

            Models\User::create([
                'email' => 'superadmin@admin.com',
                'password' => Hash::make('password123'),
                'roles_id' => $role->id,
            ]);

            $this->info("Success create role.");
        }


    }
}
