<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::where('name','admin')->first();
        $manager = Role::where('name', 'manager')->first();
        $seller = Role::where('name', 'seller')->first();
        $customer = Role::where('name', 'customer')->first();


    }
}
