<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function testRegistration()
    {
        $this
			->post('/user', [ 
				'username' => 'testuser', 
            	'email' => 'test@mail.com', 
            	'password' => 'asdRFG123'
        	])
			->seeStatusCode(201)
			->seeContent('')
    		->seeInDatabase('users', [
            	'username' => 'testuser',
            	'email' => 'test@mail.com'
        	]);
    }

    public function testLogin()
    {
        factory(App\Models\User::class, 'defined')->create();
        factory(App\Models\OauthClient::class, 'defined')->create();

        $this
			->post('/oauth/token', [ 
                'grant_type'=> 'password', 
				'scope'=> '*', 
				'client_id'=> '1', 
				'client_secret'=> 'rRAOa2gevUIoZHPz50DE0Q==', 
				'username' => 'test@mail.com', 
            	'password' => 'asdRFG123'
        	])
			->seeStatusCode(200)
			->seeJsonStructure([ 
				'token_type' => [], 
				'expires_in' => [], 
				'access_token' => [], 
				'refresh_token' => [], 
			]);
    }

    public function testUserList()
    {
        factory(App\Models\User::class, 'random', 5)->create();
        $user = factory(App\Models\User::class, 'defined')->create();

        Laravel\Passport\Passport::actingAs($user);

        $this
			->get('/user')
			->seeStatusCode(200)
			->seeJsonCount(6);
    }
    
    public function testBasicValidation()
    {
        $this
			->post('/user', [ 
				'password' => 'asdRFG123' 
			])
			->seeJsonStructure([ 
				'username' => [], 
				'email' => [], 
			])
			->seeStatusCode(400);

		$this->assertEquals(0, DB::table('users')->count());
    }

    public function testUniqueValidation()
    {
        factory(App\Models\User::class, 'defined')->create();

        $this
			->post('/user', [ 
				'username' => 'testuser', 
				'email' => 'test@mail.com', 
				'password' => 'asdRFG123' 
			])
			->seeJson([ 
				'username' => ['The username has already been taken.'], 
				'email' => ['The email has already been taken.'],
        	])
			->seeStatusCode(400);

		$this->assertEquals(1, DB::table('users')->count());
    }

    public function testPasswordFormatValidation()
    {
        $this
            ->post('/user', [
                'username' => 'testuser', 
                'email' => 'test@mail.com', 
                'password' => 'abc' 
            ]) 
            ->seeJson([ 
                'password' => [
                    'The password format is invalid.',
                    'The password must be at least 6 characters.'
                ], 
            ])
			->seeStatusCode(400);

		$this->assertEquals(0, DB::table('users')->count());
    }
}
