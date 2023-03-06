<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SectionsTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
        // $this->call(CountrySeeder::class);
        // $this->call(StatesSeeder::class);
        // $this->call(CitiesSeeder::class);
        $this->call(CmsPageSeeder::class);
        $this->call(SettingSeeder::class);

        //dummy user entry
        User::factory()->count(2)->create();
    }
}
