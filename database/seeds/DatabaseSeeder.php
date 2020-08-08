<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        $this->call(ListOfListsTableSeeder::class);
        $this->call(TodoListSeeder::class);
        factory(\App\Models\TodoItem::class, 45)->create();
    }
}
