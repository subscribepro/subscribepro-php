<?php

namespace SubscribePro\Service\Token;

use SubscribePro\Service\AbstractService;

/**
 * Config options for token service:
 * - instance_name
 *   Specified class must implement \SubscribePro\Service\Token\TokenInterface interface
 *   Default value is \SubscribePro\Service\Token\Token
 *   @see \SubscribePro\Service\Token\TokenInterface
 */
class TokenService extends AbstractService
{
    /**
     * Service name
     */
    const NAME = 'token';

    const API_NAME_TOKEN = 'token';

    /**
     * @param array $tokenData
     * @return \SubscribePro\Service\DataInterface
     */
    public function createToken(array $tokenData = [])
    {
        return $this->dataFactory->create($tokenData);
    }

    /**
     * @param string $token
     * @return \SubscribePro\Service\DataInterface
     * @throws \SubscribePro\Exception\HttpException
     */
    public function loadToken($token)
    {
        $response = $this->httpClient->get("/services/v1/vault/tokens/{$token}.json");
        return $this->retrieveItem($response, self::API_NAME_TOKEN);
    }

    /**
     * @param \SubscribePro\Service\Token\TokenInterface $token
     * @return \SubscribePro\Service\DataInterface
     * @throws \SubscribePro\Exception\HttpException
     */
    public function saveToken(TokenInterface $token)
    {
        $response = $this->httpClient->post('/services/v1/vault/token.json', [self::API_NAME_TOKEN => $token->getFormData()]);
        return $this->retrieveItem($response, self::API_NAME_TOKEN, $token);
    }
}
