<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function testRegistrationStatus()
    {
        $this->post('/user', ['username' => 'ishmum', 'password' => 'asdRFG123']);

        $this->assertEquals('', $this->response->getContent());
        $this->assertEquals(201, $this->response->getStatusCode());
    }

    public function testRegistrationDbEntry()
    {
        $this->post('/user', [
            'username' => 'test', 
            'email' => 'test@mail.com', 
            'password' => 'asdRFG123'
        ]);

        $this->seeInDatabase('users', ['username' => 'test']);
    }
}
