<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(EmailTemplateTableSeed::class);
        $this->call(GeneralSettingTableSeeder::class);
        $this->call(ServicesTableSeeder::class);
        $this->call(TestimonialsTableSeeder::class);
        $this->call(SocialLinkTableSeeder::class);
        $this->call(HowWorkTableSeeder::class);
        $this->call(RolesTableSeeder::class);

        DB::table('users')->insert([
            'name' => 'xhulian',
            'email' => 'xhulian.hysollari@gmail.com',
            'password' => Hash::make('secret'),
            'is_admin' => 1,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        DB::table('departments')->insert([
            'title' => 'Kurrikula (në shkollë dhe në klasë)',
            'description' => 'Manuali për implementimin e kurrikulës në nivel shkolle dhe klase',
        ]);
    }
}
