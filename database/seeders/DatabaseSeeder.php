<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            KategoriSeeder::class,
            KodeKlasifikasiSeeder::class,
            UnitPengolahSeeder::class,
            AdminSeeder::class,
            UserSeeder::class,
            BerkasArsipSeeder::class,
            ArsipUnitSeeder::class,
        ]);
    }
}
