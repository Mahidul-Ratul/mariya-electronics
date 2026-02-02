<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if default admin user already exists
        $adminExists = User::where('email', 'admin@mariyaelectronics.com')->exists();
        
        if (!$adminExists) {
            User::create([
                'name' => 'Mariya Electronics Admin',
                'email' => 'admin@mariyaelectronics.com',
                'password' => Hash::make('mariya123'),
                'email_verified_at' => now(),
            ]);

            $this->command->info('Default admin user created successfully!');
            $this->command->info('Email: admin@mariyaelectronics.com');
            $this->command->info('Password: mariya123');
            $this->command->warn('Please change the default password after first login.');
        } else {
            $this->command->info('Default admin user already exists.');
        }
    }
}