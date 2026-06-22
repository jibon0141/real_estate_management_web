<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommissionSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('commissions')->insert([
            ['name' => 'Sponsor', 'commission_percentage' => 0],
            ['name' => '1st Generation', 'commission_percentage' => 0],
            ['name' => '2nd Generation', 'commission_percentage' => 0],
            ['name' => '3rd Generation', 'commission_percentage' => 0],
            ['name' => '4th Generation', 'commission_percentage' => 0],
            ['name' => '5th Generation', 'commission_percentage' => 0],
        ]);
    }
}
