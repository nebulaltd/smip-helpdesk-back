<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestimonialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('testimonials')->insert([
            'name' => 'Fazle Lox',
            'designation' => 'Bank Manager at xyz',
            'image' => 'uploads/testimonials/team1.jpg',
            'comment' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('testimonials')->insert([
            'name' => 'Mahabub Lio',
            'designation' => 'Co-founder at DOT-O',
            'image' => 'uploads/testimonials/team2.jpg',
            'comment' => 'Vestibulum quis quam ut magna consequat faucibus. Pellentesque eget mi suscipit tincidunt. Utmtc tempus dictum.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('testimonials')->insert([
            'name' => 'PAULA WILSON',
            'designation' => 'Media Analyst at Bo Media',
            'image' => 'uploads/testimonials/team3.jpg',
            'comment' => 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('testimonials')->insert([
            'name' => 'MICHAEL HOLZ',
            'designation' => 'Seo Analyst at OsCort',
            'image' => 'uploads/testimonials/team4.jpg',
            'comment' => 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
