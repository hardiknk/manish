<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use App\Admin;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = [
            ['full_name' => 'Admin',
            'email' => "admin@admin.com",
            'contact_no' => '1234567890',
            'password' => \Hash::make('12345678'),
            'permissions' => serialize(getPermissions('admin')),
            'is_active' => 'y',
            'type' => 'admin',
            'profile' => NULL,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),],
            ['full_name' => 'John Doe',
            'email' => "john@example.com",
            'contact_no' => '1234567890',
            'password' => \Hash::make('12345678'),
            'permissions' => serialize(getPermissions('admin')),
            'is_active' => 'y',
            'type' => 'admin',
            'profile' => NULL,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),]

        ];

        Admin::insert($admins);
    }
}
