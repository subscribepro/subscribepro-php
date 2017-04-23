<?php

namespace SubscribePro\Tools;

class Oauth extends AbstractTool
{
    /**
     * Tool name
     */
    const NAME = 'oauth';

    /**
     * @param array $params
     * @return array
     */
    public function retrieveAccessToken(array $params)
    {
        $params = array_merge([
            'grant_type' => 'client_credentials',
            'scope' => 'client',
        ], $params);

        $response = $this->httpClient->get("/oauth/v2/token?" . http_build_query($params));

        return is_array($response) ? $response : [];
    }

    /**
     * @param $customerId
     * @return array
     */
    public function retrieveWidgetAccessTokenByCustomerId($customerId)
    {
        return $this->retrieveAccessToken([
            'scope' => 'widget',
            'customer_id' => $customerId,
        ]);
    }

    /**
     * @param string $customerEmail
     * @return array
     */
    public function retrieveWidgetAccessTokenByCustomerEmail($customerEmail)
    {
        return $this->retrieveAccessToken([
            'scope' => 'widget',
            'customer_email' => $customerEmail,
        ]);
    }

}
