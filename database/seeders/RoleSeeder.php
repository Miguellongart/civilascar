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
        
        $admin = Role::updateOrCreate(['name' => 'admin', 'guard_name' => 'web'], ['description' => 'Rol Administrador']);
        $teacher = Role::updateOrCreate(['name' => 'teacher', 'guard_name' => 'web'], ['description' => 'Rol Profesor']);
        $player = Role::updateOrCreate(['name' => 'player', 'guard_name' => 'web'], ['description' => 'Rol jugador']);
        $customer = Role::updateOrCreate(['name' => 'customer', 'guard_name' => 'web'], ['description' => 'Rol Cliente']);
        
        /*home*/
        Permission::updateOrCreate(['name' => 'admin.home'],
            ['description' => 'Ver Home'])->syncRoles([$admin, $teacher, $player, $customer]);

        /*Asginar*/
        Permission::updateOrCreate(['name' => 'admin.assign.roles'],
            ['description' => 'Asignar/Retirar roles al usuario'])->syncRoles([$admin]);
        Permission::updateOrCreate(['name' => 'admin.assign.permissions'],
            ['description' => 'Asignar/Retirar permisos al usuario'])->syncRoles([$admin]);

        /*usuarios*/
        Permission::updateOrCreate(
            ['name' => 'admin.user.index'], 
            ['description' => 'Listado Usuarios'])->syncRoles([$admin, $teacher, $player, $customer]);
        Permission::updateOrCreate(
            ['name' => 'admin.user.create'], 
            ['description' => 'Crear usuario'])->syncRoles([$admin]);
        Permission::updateOrCreate(
            ['name' => 'admin.user.edit'], 
            ['description' => 'Editar usuario'])->syncRoles([$admin]);
        Permission::updateOrCreate(
            ['name' => 'admin.user.destroy'], 
            ['description' => 'Eliminar usuario'])->syncRoles([$admin]);

        /*roles*/
        Permission::updateOrCreate(
            ['name' => 'admin.rol.index'], 
            ['description' => 'Listado roles'])->syncRoles([$admin, $teacher, $player]);
        Permission::updateOrCreate(
            ['name' => 'admin.rol.create'], 
            ['description' => 'Crear rol'])->syncRoles([$admin]);
        Permission::updateOrCreate(
            ['name' => 'admin.rol.edit'], 
            ['description' => 'Editar rol'])->syncRoles([$admin]);
        Permission::updateOrCreate(
            ['name' => 'admin.rol.destroy'], 
            ['description' => 'Eliminar rol'])->syncRoles([$admin]);
            
        /*permisos*/
        Permission::updateOrCreate(
            ['name' => 'admin.permissions.index'], 
            ['description' => 'Listado permisos'])->syncRoles([$admin, $teacher, $player]);
        Permission::updateOrCreate(
            ['name' => 'admin.permissions.create'], 
            ['description' => 'Crear permiso'])->syncRoles([$admin]);
        Permission::updateOrCreate(
            ['name' => 'admin.permissions.edit'], 
            ['description' => 'Editar permiso'])->syncRoles([$admin]);
        Permission::updateOrCreate(
            ['name' => 'admin.permissions.destroy'], 
            ['description' => 'Eliminar permiso'])->syncRoles([$admin]);


        /*tournamen*/
        Permission::updateOrCreate(
            ['name' => 'admin.tournamen.index'], 
            ['description' => 'Listado Torneos'])->syncRoles([$admin, $teacher, $player, $customer]);
        Permission::updateOrCreate(
            ['name' => 'admin.tournamen.create'], 
            ['description' => 'Crear Torneo'])->syncRoles([$admin]);
        Permission::updateOrCreate(
            ['name' => 'admin.tournamen.edit'], 
            ['description' => 'Editar Torneo'])->syncRoles([$admin]);
        Permission::updateOrCreate(
            ['name' => 'admin.tournamen.destroy'], 
            ['description' => 'Eliminar Torneo'])->syncRoles([$admin]);


        /*equipo*/
        Permission::updateOrCreate(
            ['name' => 'admin.teams.index'], 
            ['description' => 'Listado Equipos'])->syncRoles([$admin, $teacher, $player, $customer]);
        Permission::updateOrCreate(
            ['name' => 'admin.teams.create'], 
            ['description' => 'Crear Equipo'])->syncRoles([$admin]);
        Permission::updateOrCreate(
            ['name' => 'admin.teams.edit'], 
            ['description' => 'Editar Equipo'])->syncRoles([$admin]);
        Permission::updateOrCreate(
            ['name' => 'admin.teams.destroy'], 
            ['description' => 'Eliminar Equipo'])->syncRoles([$admin]);

    }
}
