<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EquipoSeeder extends Seeder
{
    public function run()
    {
        DB::table('equipos')->truncate();

        $data = [
            // --- SERIE ESTÁNDAR (EQ001...) ---
            ['codigo' => 'EQ001', 'cliente' => 'Wayne Ent', 'tipo' => 'Tablet', 'estado' => 'alquilado', 'fecha_entrega' => Carbon::now()->addDays(45)->format('Y-m-d')],
            ['codigo' => 'EQ002', 'cliente' => 'Globex', 'tipo' => 'Laptop', 'estado' => 'disponible', 'fecha_entrega' => null],
            ['codigo' => 'EQ003', 'cliente' => 'Initech', 'tipo' => 'Teclado', 'estado' => 'disponible', 'fecha_entrega' => null],
            
            // --- SERIE CON GUIONES (EQ-XXX) PARA PROBAR FILTROS ---
            ['codigo' => 'EQ-A10', 'cliente' => 'Umbrella Corp', 'tipo' => 'Proyector', 'estado' => 'mantenimiento', 'fecha_entrega' => null],
            ['codigo' => 'EQ-A11', 'cliente' => 'Globex', 'tipo' => 'Proyector', 'estado' => 'disponible', 'fecha_entrega' => null],
            ['codigo' => 'EQ-B20', 'cliente' => 'Wayne Ent', 'tipo' => 'Laptop', 'estado' => 'mantenimiento', 'fecha_entrega' => null],
            ['codigo' => 'EQ-X99', 'cliente' => 'Cyberdyne', 'tipo' => 'Monitor', 'estado' => 'mantenimiento', 'fecha_entrega' => null],
            ['codigo' => 'EQ-2024', 'cliente' => 'Massive Dynamic', 'tipo' => 'Proyector', 'estado' => 'disponible', 'fecha_entrega' => null],
            ['codigo' => 'EQ-7500', 'cliente' => 'Stark Ind', 'tipo' => 'Tablet', 'estado' => 'disponible', 'fecha_entrega' => null],
            
            // --- MÁS DATOS VARIADOS ---
            ['codigo' => 'EQ004', 'cliente' => 'Initech', 'tipo' => 'Monitor', 'estado' => 'mantenimiento', 'fecha_entrega' => null],
            ['codigo' => 'EQ005', 'cliente' => 'Globex', 'tipo' => 'Monitor', 'estado' => 'alquilado', 'fecha_entrega' => Carbon::now()->addDays(43)->format('Y-m-d')],
            ['codigo' => 'EQ-HYBRID', 'cliente' => 'Tyrell Corp', 'tipo' => 'Servidor', 'estado' => 'mantenimiento', 'fecha_entrega' => null],
            ['codigo' => 'EQ006', 'cliente' => 'Stark Ind', 'tipo' => 'Mouse', 'estado' => 'alquilado', 'fecha_entrega' => Carbon::now()->addDays(25)->format('Y-m-d')],
            
            // --- REGISTROS ADICIONALES PARA LLENAR LA TABLA ---
            ['codigo' => 'EQ010', 'cliente' => 'Wayne Ent', 'tipo' => 'Laptop', 'estado' => 'mantenimiento', 'fecha_entrega' => null],
            ['codigo' => 'EQ-Z100', 'cliente' => 'Cyberdyne', 'tipo' => 'Monitor', 'estado' => 'mantenimiento', 'fecha_entrega' => null],
            ['codigo' => 'EQ012', 'cliente' => 'Massive Dynamic', 'tipo' => 'Proyector', 'estado' => 'disponible', 'fecha_entrega' => null],
            ['codigo' => 'EQ-TEST-1', 'cliente' => 'Aperture Science', 'tipo' => 'Tablet', 'estado' => 'disponible', 'fecha_entrega' => null],
            ['codigo' => 'EQ014', 'cliente' => 'Wayne Ent', 'tipo' => 'Proyector', 'estado' => 'disponible', 'fecha_entrega' => null],
            ['codigo' => 'EQ-SPECIAL', 'cliente' => 'Black Mesa', 'tipo' => 'Servidor', 'estado' => 'alquilado', 'fecha_entrega' => Carbon::now()->addDays(10)->format('Y-m-d')],
            ['codigo' => 'EQ016', 'cliente' => 'Umbrella Corp', 'tipo' => 'Teclado', 'estado' => 'alquilado', 'fecha_entrega' => Carbon::now()->addDays(32)->format('Y-m-d')],
            ['codigo' => 'EQ017', 'cliente' => 'Wayne Ent', 'tipo' => 'Proyector', 'estado' => 'disponible', 'fecha_entrega' => null],
            ['codigo' => 'EQ-MX5', 'cliente' => 'Initech', 'tipo' => 'Mouse', 'estado' => 'alquilado', 'fecha_entrega' => Carbon::now()->addDays(7)->format('Y-m-d')],
            ['codigo' => 'EQ019', 'cliente' => 'Massive Dynamic', 'tipo' => 'Monitor', 'estado' => 'alquilado', 'fecha_entrega' => Carbon::now()->addDays(34)->format('Y-m-d')],
            ['codigo' => 'EQ-ALPHA', 'cliente' => 'Acme Corp', 'tipo' => 'Teclado', 'estado' => 'alquilado', 'fecha_entrega' => Carbon::now()->addDays(26)->format('Y-m-d')],
            ['codigo' => 'EQ021', 'cliente' => 'Stark Ind', 'tipo' => 'Monitor', 'estado' => 'mantenimiento', 'fecha_entrega' => null],
        ];

        DB::table('equipos')->insert($data);
    }
}