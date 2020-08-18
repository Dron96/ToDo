<?php

use App\Models\TodoItem;
use App\Models\User;
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
        for($i = 1; $i < 3; $i++) {
            $user = factory(User::class)->create();
            $user->createToken('authToken')->accessToken;
        }

        $this->call(ListOfListsTableSeeder::class);
        $this->call(TodoListSeeder::class);
        factory(TodoItem::class, 45)->create();
    }
}
