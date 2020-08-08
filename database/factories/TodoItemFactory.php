<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TodoItem;
use Faker\Generator as Faker;

$factory->define(TodoItem::class, function (Faker $faker) {
//    $name = $faker->text(rand(3, 15));
    $description = $faker->realText(rand(500, 1500));
    $isComplete = rand(1, 5) > 3;
    $urgency = rand(1, 5);
    $list_id = rand(1, 9);
    $created_at = $faker->dateTimeBetween('-3 monts','-10 day');



    return [
        'name' => $faker->realText(rand(20,255)),
        'description' => $description,
        'complete' => $isComplete,
        'urgency' => $urgency,
        'list_id' => $list_id,
        'created_at' => $created_at,
        'updated_at' => $created_at,
    ];
});
