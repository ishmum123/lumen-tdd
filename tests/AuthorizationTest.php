<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthorizationTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function testUserListNeedsAuthentication()
    {
        factory(App\Models\User::class, 'random', 5)->create();

        $this
			->get('/user')
			->seeStatusCode(401)
			->seeContent('Unauthorized.');
    }
}
