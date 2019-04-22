<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

// TODO: Test Invalid JSON Request Response should be 422
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

    public function testBasicDataValidation()
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

    public function testInvalidJson()
    {
        $response = $this->call('POST', '/user', [], [], [], [], '{');
        $this->seeJsonEquals([
            'error' => [ 
                'code' => 422,
                'message' => 'Invalid JSON Structure'
            ]
        ], $response->getContent());
    }

    public function testUnknownUrl()
    {
        $this
            ->get('/non-existent-url')
            ->seeJsonEquals([ 
                'error' => [ 
                    'code' => 404, 
                    'message' => 'URL Not Found' 
                ] 
            ]);
    }
}
