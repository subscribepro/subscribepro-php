<?php

namespace SubscribePro;

class App
{
    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $clientSecret;

    /**
     * @param string $id
     * @param string $secret
     */
    public function __construct($id, $secret)
    {
        $this->clientId = $id;
        $this->clientSecret = $secret;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }
}
