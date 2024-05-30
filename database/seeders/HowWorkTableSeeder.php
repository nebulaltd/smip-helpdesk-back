<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HowWorkTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('how_works')->insert([
            'title' => 'Listen From Clients',
            'icon' => 'fa fa-question',
            'details' => 'Proin dapibus nisl ornare diam varius tempus. Aenean a quam luctus, finibus tellus ut, convallis eros sollicitudin turpis.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('how_works')->insert([
            'title' => 'Find The Problem',
            'icon' => 'fa fa-dot-circle-o',
            'details' => 'Faucibus ante, in porttitor tellus blandit et. Phasellus tincidunt metus lectus sollicitudin feugiat pharetra consectetur.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
		DB::table('how_works')->insert([
            'title' => 'Provide Right Solutions',
            'icon' => 'fa fa-check',
            'details' => 'Maecenas pulvinar, risus in facilisis dignissim, quam nisi hendrerit nulla, id vestibulum metus nullam viverra porta.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
    }
}
