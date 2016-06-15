<?php

namespace SubscribePro\Tests;

use SubscribePro\App;

class AppTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SubscribePro\App
     */
    protected $app;

    /**
     * @var string
     */
    protected $clientId = 'client_id';

    /**
     * @var string
     */
    protected $clientSecret = 'client_secret';

    public function setUp()
    {
        $this->app = new App($this->clientId, $this->clientSecret);
    }

    public function testClientId()
    {
        $this->assertEquals($this->clientId, $this->app->getClientId());
    }

    public function testGetClientSecret()
    {
        $this->assertEquals($this->clientSecret, $this->app->getClientSecret());
    }
}
