<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\currency;

class DefaultDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->insert([
            [
                'name' => 'eur',
                'created_at' => now(),
                'updated_at' => now(),
            ] , [
                'name' => 'usd',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ],);
    }
}
