<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Lookup tables (no dependencies) ──────────────
        $this->call(RolePermissionSeeder::class);
        $this->call(ReligionsSeeder::class);
        $this->call(MotherTonguesSeeder::class);
        $this->call(EducationLevelsSeeder::class);
        $this->call(ProfessionsSeeder::class);
        $this->call(AnnualIncomeRangesSeeder::class);
        $this->call(RashisSeeder::class);
        $this->call(NakshatrasSeeder::class);

        // ── Lookup tables (with FK dependencies) ─────────
        $this->call(CastesSeeder::class);
        $this->call(CountriesStatesCitiesSeeder::class);

        // ── Demo user ───────────────────────────────────
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
