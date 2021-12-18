<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'key' => 'cedis_to_naira',
            'label' => 'Cedis Rate',
            'description' => 'The accepted conversion rate for 1 Cedis to Naira.',
            'type' => 'float',
            'inputlabel' => '&#8358;'
        ]);
    }
}
