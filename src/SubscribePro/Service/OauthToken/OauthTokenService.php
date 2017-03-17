<?php

namespace SubscribePro\Service\OauthToken;

use SubscribePro\Service\AbstractService;

class OauthTokenService extends AbstractService
{
    /**
     * Service name
     */
    const NAME = 'oauth_token';

    const OAUTH_TOKEN = 'access_token';

    /**
     * @param string $customerId
     * @return string
     */
    public function retrieveToken($customerEmail)
    {
        $email = urlencode($customerEmail);
        try {
            $response = $this->httpClient->get("/oauth/v2/token?grant_type=client_credentials&scope=widget&customer_email=$email");
        } catch (\SubscribePro\Exception\HttpException $e) {
            return false;
        }

        return (isset($response[self::OAUTH_TOKEN])) ? $response[self::OAUTH_TOKEN] : false;
    }
}

