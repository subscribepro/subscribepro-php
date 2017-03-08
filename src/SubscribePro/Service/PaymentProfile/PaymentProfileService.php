<?php

namespace SubscribePro\Service\PaymentProfile;

use SubscribePro\Exception\EntityInvalidDataException;
use SubscribePro\Service\AbstractService;
use SubscribePro\Exception\InvalidArgumentException;

/**
 * Config options for payment profile service:
 * - instance_name
 *   Specified class must implement \SubscribePro\Service\PaymentProfile\PaymentProfileInterface interface
 *   Default value is \SubscribePro\Service\PaymentProfile\PaymentProfile
 *   @see \SubscribePro\Service\PaymentProfile\PaymentProfileInterface
 */
class PaymentProfileService extends AbstractService
{
    /**
     * Service name
     */
    const NAME = 'payment_profile';

    const API_NAME_PROFILE = 'payment_profile';
    const API_NAME_PROFILES = 'payment_profiles';

    /**
     * @var string
     */
    protected $allowedFilters = [
        PaymentProfileInterface::CUSTOMER_ID,
        PaymentProfileInterface::MAGENTO_CUSTOMER_ID,
        PaymentProfileInterface::CUSTOMER_EMAIL,
        PaymentProfileInterface::PROFILE_TYPE,
        PaymentProfileInterface::PAYMENT_METHOD_TYPE
    ];

    /**
     * @param array $paymentProfileData
     * @return \SubscribePro\Service\PaymentProfile\PaymentProfileInterface
     */
    public function createProfile(array $paymentProfileData = [])
    {
        return $this->dataFactory->create($paymentProfileData);
    }

    /**
     * @param \SubscribePro\Service\PaymentProfile\PaymentProfileInterface $paymentProfile
     * @return \SubscribePro\Service\PaymentProfile\PaymentProfileInterface
     * @throws \SubscribePro\Exception\EntityInvalidDataException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function saveProfile(PaymentProfileInterface $paymentProfile)
    {

        switch($paymentProfile->getPaymentMethodType()) {
            case PaymentProfileInterface::TYPE_BANK_ACCOUNT:
                return $this->saveBankAccountProfile($paymentProfile);
            case PaymentProfileInterface::TYPE_APPLE_PAY:
                return $this->saveApplePayProfile($paymentProfile);
            case PaymentProfileInterface::TYPE_CREDIT_CARD:
            default:
                return $this->saveCreditCardProfile($paymentProfile);
        }

    }

    /**
     * @param \SubscribePro\Service\PaymentProfile\PaymentProfileInterface $paymentProfile
     * @return \SubscribePro\Service\PaymentProfile\PaymentProfileInterface
     * @throws \SubscribePro\Exception\EntityInvalidDataException
     * @throws \SubscribePro\Exception\HttpException
     */
    private function saveCreditCardProfile(PaymentProfileInterface $paymentProfile)
    {
        if (!$paymentProfile->isValid()) {
            throw new EntityInvalidDataException('Not all required fields are set.');
        }
        $postData = [self::API_NAME_PROFILE => $paymentProfile->getFormData()];
        $response = $paymentProfile->isNew()
            ? $this->httpClient->post('/services/v2/vault/paymentprofile/creditcard.json', $postData)
            : $this->httpClient->put("/services/v1/vault/paymentprofiles/{$paymentProfile->getId()}.json", $postData);
        return $this->retrieveItem($response, self::API_NAME_PROFILE, $paymentProfile);
    }

    /**
     * @param \SubscribePro\Service\PaymentProfile\PaymentProfileInterface $paymentProfile
     * @return \SubscribePro\Service\PaymentProfile\PaymentProfileInterface
     * @throws \SubscribePro\Exception\EntityInvalidDataException
     * @throws \SubscribePro\Exception\HttpException
     */
    private function saveApplePayProfile(PaymentProfileInterface $paymentProfile)
    {
        if (!$paymentProfile->isValid()) {
            throw new EntityInvalidDataException('Not all required fields are set.');
        }
        $postData = [self::API_NAME_PROFILE => $paymentProfile->getFormData()];
        $response = $paymentProfile->isNew()
            ? $this->httpClient->post('/services/v2/vault/paymentprofile/applepay.json', $postData)
            : $this->httpClient->put("/services/v1/vault/paymentprofiles/{$paymentProfile->getId()}.json", $postData);
        return $this->retrieveItem($response, self::API_NAME_PROFILE, $paymentProfile);
    }

    /**
     * @param \SubscribePro\Service\PaymentProfile\PaymentProfileInterface $paymentProfile
     * @return \SubscribePro\Service\PaymentProfile\PaymentProfileInterface
     * @throws \SubscribePro\Exception\EntityInvalidDataException
     * @throws \SubscribePro\Exception\HttpException
     */
    private function saveBankAccountProfile(PaymentProfileInterface $paymentProfile)
    {
        if (!$paymentProfile->isValid()) {
            throw new EntityInvalidDataException('Not all required fields are set.');
        }
        $postData = [self::API_NAME_PROFILE => $paymentProfile->getFormData()];
        $response = $paymentProfile->isNew()
            ? $this->httpClient->post('/services/v2/vault/paymentprofile/bankaccount.json', $postData)
            : $this->httpClient->put("/services/v1/vault/paymentprofiles/{$paymentProfile->getId()}.json", $postData);
        return $this->retrieveItem($response, self::API_NAME_PROFILE, $paymentProfile);
    }

    /**
     * @param int $paymentProfileId
     * @return \SubscribePro\Service\PaymentProfile\PaymentProfileInterface
     * @throws \SubscribePro\Exception\HttpException
     */
    public function redactProfile($paymentProfileId)
    {
        $response = $this->httpClient->put("/services/v1/vault/paymentprofiles/{$paymentProfileId}/redact.json");
        return $this->retrieveItem($response, self::API_NAME_PROFILE);
    }

    /**
     * @param int $paymentProfileId
     * @return \SubscribePro\Service\PaymentProfile\PaymentProfileInterface
     * @throws \SubscribePro\Exception\HttpException
     */
    public function loadProfile($paymentProfileId)
    {
        $response = $this->httpClient->get("/services/v1/vault/paymentprofiles/{$paymentProfileId}.json");
        return $this->retrieveItem($response, self::API_NAME_PROFILE);
    }

    /**
     * Retrieve an array of all payment profiles.
     *  Available filters:
     * - magento_customer_id
     * - customer_email
     * - profile_type
     * - payment_method_type
     *
     * @param array $filters
     * @return \SubscribePro\Service\PaymentProfile\PaymentProfileInterface[]
     * @throws \SubscribePro\Exception\HttpException
     */
    public function loadProfiles(array $filters = [])
    {
        $invalidFilters = array_diff_key($filters, array_flip($this->allowedFilters));
        if (!empty($invalidFilters)) {
            throw new InvalidArgumentException(
                'Only [' . implode(', ', $this->allowedFilters) . '] query filters are allowed.'
            );
        }

        $response = $this->httpClient->get('/services/v1/vault/paymentprofiles.json', $filters);
        return $this->retrieveItems($response, self::API_NAME_PROFILES);
    }

    /**
     * @param \SubscribePro\Service\PaymentProfile\PaymentProfileInterface $paymentProfile
     * @return \SubscribePro\Service\PaymentProfile\PaymentProfileInterface
     * @throws \SubscribePro\Exception\EntityInvalidDataException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function saveThirdPartyToken(PaymentProfileInterface $paymentProfile)
    {
        if (!$paymentProfile->isThirdPartyDataValid()) {
            throw new EntityInvalidDataException('Not all required fields are set.');
        }

        $response = $this->httpClient->post(
            '/services/v2/paymentprofile/third-party-token.json',
            [self::API_NAME_PROFILE => $paymentProfile->getThirdPartyTokenFormData()]
        );
        return $this->retrieveItem($response, self::API_NAME_PROFILE, $paymentProfile);
    }

    /**
     * @param string $token
     * @param \SubscribePro\Service\PaymentProfile\PaymentProfileInterface $paymentProfile
     * @return \SubscribePro\Service\PaymentProfile\PaymentProfileInterface
     * @throws \SubscribePro\Exception\EntityInvalidDataException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function saveToken($token, PaymentProfileInterface $paymentProfile)
    {
        if (!$paymentProfile->isTokenDataValid()) {
            throw new EntityInvalidDataException('Not all required fields are set.');
        }

        $response = $this->httpClient->post(
            "/services/v1/vault/tokens/{$token}/store.json",
            ['payment_profile' => $paymentProfile->getTokenFormData()]
        );
        return $this->retrieveItem($response, self::API_NAME_PROFILE, $paymentProfile);
    }

    /**
     * @param string $token
     * @param \SubscribePro\Service\PaymentProfile\PaymentProfileInterface $paymentProfile
     * @return \SubscribePro\Service\PaymentProfile\PaymentProfileInterface
     * @throws \SubscribePro\Exception\EntityInvalidDataException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function verifyAndSaveToken($token, PaymentProfileInterface $paymentProfile)
    {
        if (!$paymentProfile->isTokenDataValid()) {
            throw new EntityInvalidDataException('Not all required fields are set.');
        }

        $response = $this->httpClient->post(
            "/services/v1/vault/tokens/{$token}/verifyandstore.json",
            ['payment_profile' => $paymentProfile->getTokenFormData()]
        );
        return $this->retrieveItem($response, self::API_NAME_PROFILE, $paymentProfile);
    }

    /**
     * @param string $token
     * @return \SubscribePro\Service\PaymentProfile\PaymentProfileInterface
     * @throws \SubscribePro\Exception\HttpException
     */
    public function loadProfileByToken($token)
    {
        $response = $this->httpClient->get("/services/v1/vault/tokens/{$token}/paymentprofile.json");
        return $this->retrieveItem($response, self::API_NAME_PROFILE);
    }
}
