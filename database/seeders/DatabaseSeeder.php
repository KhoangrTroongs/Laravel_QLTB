<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\EquipmentUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'employee_id' => 'ADMIN001',
            'name' => 'Administartor',
            'email'=> env('ADMIN_MAIL'),
            'password' => env('ADMIN_PASS'),
        ]);
    
        User::factory(99)->create();

        Equipment::factory(100)->create();

        EquipmentUser::factory(200)->create();
    }

}
