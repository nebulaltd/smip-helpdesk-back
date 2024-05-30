<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->insert([
            'title' => 'What is Lorem Ipsum?',
            'icon' => 'fa fa-bandcamp',
            'details' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('services')->insert([
            'title' => 'Why do we use it?',
            'icon' => 'fa fa-handshake-o',
            'details' => 'All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary text, making this the first true generator on the Internet.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('services')->insert([
            'title' => 'Where does it come from?',
            'icon' => 'fa fa-meetup',
            'details' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
