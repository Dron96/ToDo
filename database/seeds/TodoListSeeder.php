<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class TodoListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $list = [];

        for ($i = 1; $i <= 9; $i++){
            $name = $faker->realText(rand(10, 55));
            $list_id = rand(1, 3);
            $created_at = $faker->dateTimeBetween('-3 monts','-10 day');

            $list[] = [
                'name' => $name,
                'list_id' => $list_id,
                'created_at' => $created_at,
                'updated_at' => $created_at,
            ];
        }

        DB::table('todo_lists')->insert($list);
    }
}
