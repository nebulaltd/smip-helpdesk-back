<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocialLinkTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('socials')->insert([
            'name' => 'Facebook',
            'code' => 'fa fa-facebook',
            'link' => 'http://facebook.com',
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ]);

        DB::table('socials')->insert([
            'name' => 'Youtube',
            'code' => 'fa fa-youtube',
            'link' => 'http://youtube.com',
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ]);

        DB::table('socials')->insert([
            'name' => 'Twitter',
            'code' => 'fa fa-twitter',
            'link' => 'http://twitter.com',
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ]);

        DB::table('socials')->insert([
            'name' => 'Linkedin',
            'code' => 'fa fa-linkedin-square',
            'link' => 'http://linkedin.com',
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ]);
    }
}
