<?php

namespace SubscribePro\Service\PaymentProfile;

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
    public function retrieveToken($customerId)
    {
        $response = $this->httpClient->post("/oauth/v2/token?grant_type=client_credentials&scope=widget&customer_id=$customerId");
        return $this->retrieveItem($response, self::OAUTH_TOKEN);
    }
}

