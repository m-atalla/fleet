<?php

namespace Database\Seeders;

use App\Models\Station;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ["name" => "Cairo"],
            ["name" => "Giza"],
            ["name" => "AlFayyum"],
            ["name" => "AlMinya"],
            ["name" => "Asyut"],
        ];

        DB::table("stations")->insert($cities);
    }
}
