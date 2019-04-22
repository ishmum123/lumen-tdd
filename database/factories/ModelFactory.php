<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->defineAs(App\Models\User::class, 'random', function (Faker\Generator $faker) {
    return [
        'username' => $faker->name,
        'email' => $faker->email,
        'password' => app('hash')->make($faker->name),
    ];
});

$factory->defineAs(App\Models\User::class, 'defined', function (Faker\Generator $faker) {
    return [
        'username' => 'testuser',
        'email' => 'test@mail.com',
        'password' => app('hash')->make($faker->name),
    ];
});
