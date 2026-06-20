<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesignationSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('designations')->insert([
            ['name' => 'Associate Partner'],
            ['name' => 'Property Partner'],
            ['name' => 'Manager'],
            ['name' => 'General Manager'],
            ['name' => 'Deputy Director'],
            ['name' => 'Director'],
        ]);
    }
}
