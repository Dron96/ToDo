<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class ListOfListsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $lists = [];

        for ($i = 1; $i <= 3; $i++){
            $name = 'Список №'.$i;
            $created_at = $faker->dateTimeBetween('-3 monts','-10 day');

            $lists[] = ['name' => $name,
                'created_at' => $created_at,
                'updated_at' => $created_at,
                ];
        }

        DB::table('list_of_lists')->insert($lists);

    }
}
