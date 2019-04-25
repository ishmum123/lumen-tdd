<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class RoutingTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

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
    
    public function testUndefinedMethod()
    {
        $this
            ->put('/user')
            ->seeJsonEquals([ 
                'error' => [ 
                    'code' => 405, 
                    'message' => 'HTTP Method Not Allowed' 
                ] 
            ]);
    }
}
