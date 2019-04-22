<?php

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    protected function seeJsonCount($count) 
    {
        $this->assertEquals($count, count(json_decode($this->response->getContent(), TRUE)));
        return $this;
    }

    protected function seeContent() 
    {
        $this->expectOutputString('');
        return $this;
    }
}
