<?php

namespace SubscribePro\Service\Transaction;

use SubscribePro\Exception\EntityInvalidDataException;
use SubscribePro\Service\AbstractService;
use SubscribePro\Service\Address\AddressInterface;

/**
 * Config options for transaction service:
 * - instance_name
 *   Specified class must implement \SubscribePro\Service\Transaction\TransactionInterface interface
 *   Default value is \SubscribePro\Service\Transaction\Transaction
 *   @see \SubscribePro\Service\Transaction\TransactionInterface
 *
 * @method \SubscribePro\Service\Transaction\TransactionInterface retrieveItem($response, $entityName, \SubscribePro\Service\DataInterface $item = null)
 * @method \SubscribePro\Service\Transaction\TransactionInterface[] retrieveItems($response, $entitiesName)
 * @property \SubscribePro\Service\Transaction\TransactionFactory $dataFactory
 */
class TransactionService extends AbstractService
{
    /**
     * Service name
     */
    const NAME = 'transaction';

    const API_NAME_TRANSACTION = 'transaction';

    /**
     * @param array $transactionData
     * @return \SubscribePro\Service\Transaction\TransactionInterface
     */
    public function createTransaction(array $transactionData = [])
    {
        return $this->dataFactory->create($transactionData);
    }

    /**
     * @param int $transactionId
     * @return \SubscribePro\Service\Transaction\TransactionInterface
     * @throws \SubscribePro\Exception\HttpException
     */
    public function loadTransaction($transactionId)
    {
        $response = $this->httpClient->get("/services/v1/vault/transactions/{$transactionId}.json");
        return $this->retrieveItem($response, self::API_NAME_TRANSACTION);
    }

    /**
     * @param int $paymentProfileId
     * @param \SubscribePro\Service\Transaction\TransactionInterface $transaction
     * @return \SubscribePro\Service\Transaction\TransactionInterface
     * @throws \SubscribePro\Exception\EntityInvalidDataException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function verifyProfile($paymentProfileId, TransactionInterface $transaction)
    {
        if (!$transaction->isVerifyDataValid()) {
            throw new EntityInvalidDataException('Not all required fields are set.');
        }

        $response = $this->httpClient->post(
            "/services/v1/vault/paymentprofiles/{$paymentProfileId}/verify.json",
            [self::API_NAME_TRANSACTION => $transaction->getVerifyFormData()]
        );
        return $this->retrieveItem($response, self::API_NAME_TRANSACTION, $transaction);
    }

    /**
     * @param string $token
     * @param \SubscribePro\Service\Transaction\TransactionInterface $transaction
     * @param \SubscribePro\Service\Address\AddressInterface|null $address
     * @return \SubscribePro\Service\Transaction\TransactionInterface
     * @throws \SubscribePro\Exception\EntityInvalidDataException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function verifyAndStoreToken($token, TransactionInterface $transaction, AddressInterface $address = null)
    {
        if (!$transaction->isTokenDataValid()) {
            throw new EntityInvalidDataException('Not all required Transaction fields are set');
        }

        if ($address && !$address->isAsChildValid(true)) {
            throw new EntityInvalidDataException('Not all required Address fields are set');
        }

        $response = $this->httpClient->post(
            '/services/v1/vault/tokens/{$token}/verifyandstore.json',
            [self::API_NAME_TRANSACTION => $transaction->getTokenFormData($address)]
        );
        return $this->retrieveItem($response, self::API_NAME_TRANSACTION, $transaction);
    }

    /**
     * @param array $authorizeData
     * @param \SubscribePro\Service\Transaction\TransactionInterface $transaction
     * @return \SubscribePro\Service\Transaction\TransactionInterface
     * @throws \SubscribePro\Exception\EntityInvalidDataException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function authorizeByProfile($authorizeData, TransactionInterface $transaction)
    {
        $formData = $transaction->getFormData();
        if (isset($authorizeData['subscribe_pro_order_token'])) {
            $formData['subscribe_pro_order_token'] = $authorizeData['subscribe_pro_order_token'];
        }

        $profileId = $authorizeData['profile_id'];

        $response = $this->httpClient->post(
            "/services/v1/vault/paymentprofiles/{$profileId}/authorize.json",
            [self::API_NAME_TRANSACTION => $formData]
        );
        return $this->retrieveItem($response, self::API_NAME_TRANSACTION, $transaction);
    }

    /**
     * @param array $authorizeData
     * @param \SubscribePro\Service\Transaction\TransactionInterface $transaction
     * @return \SubscribePro\Service\Transaction\TransactionInterface
     * @throws \SubscribePro\Exception\EntityInvalidDataException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function purchaseByProfile($authorizeData, TransactionInterface $transaction)
    {
        $formData = $transaction->getFormData();
        if (isset($authorizeData['subscribe_pro_order_token'])) {
            $formData['subscribe_pro_order_token'] = $authorizeData['subscribe_pro_order_token'];
        }

        $profileId = $authorizeData['profile_id'];

        $response = $this->httpClient->post(
            "/services/v1/vault/paymentprofiles/{$profileId}/purchase.json",
            [self::API_NAME_TRANSACTION => $formData]
        );
        return $this->retrieveItem($response, self::API_NAME_TRANSACTION, $transaction);
    }

    /**
     * @param string $token
     * @param \SubscribePro\Service\Transaction\TransactionInterface $transaction
     * @param \SubscribePro\Service\Address\AddressInterface|null $address
     * @return \SubscribePro\Service\Transaction\TransactionInterface
     * @throws \SubscribePro\Exception\EntityInvalidDataException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function authorizeByToken($token, TransactionInterface $transaction, AddressInterface $address = null)
    {
        if (!$transaction->isTokenDataValid()) {
            throw new EntityInvalidDataException('Not all required Transaction fields are set.');
        }

        if ($address && !$address->isAsChildValid(true)) {
            throw new EntityInvalidDataException('Not all required Address fields are set.');
        }

        $response = $this->httpClient->post(
            "/services/v1/vault/tokens/{$token}/authorize.json",
            [self::API_NAME_TRANSACTION => $transaction->getTokenFormData($address)]
        );
        return $this->retrieveItem($response, self::API_NAME_TRANSACTION, $transaction);
    }

    /**
     * @param string $token
     * @param \SubscribePro\Service\Transaction\TransactionInterface $transaction
     * @param \SubscribePro\Service\Address\AddressInterface|null $address
     * @return \SubscribePro\Service\Transaction\TransactionInterface
     * @throws \SubscribePro\Exception\EntityInvalidDataException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function purchaseByToken($token, TransactionInterface $transaction, AddressInterface $address = null)
    {
        if (!$transaction->isTokenDataValid()) {
            throw new EntityInvalidDataException('Not all required Transaction fields are set.');
        }

        if ($address && !$address->isAsChildValid(true)) {
            throw new EntityInvalidDataException('Not all required Address fields are set.');
        }

        $response = $this->httpClient->post(
            "/services/v1/vault/tokens/{$token}/purchase.json",
            [self::API_NAME_TRANSACTION => $transaction->getTokenFormData($address)]
        );
        return $this->retrieveItem($response, self::API_NAME_TRANSACTION, $transaction);
    }

    /**
     * @param int $transactionId
     * @param \SubscribePro\Service\Transaction\TransactionInterface|null $transaction
     * @return \SubscribePro\Service\Transaction\TransactionInterface
     * @throws \SubscribePro\Exception\EntityInvalidDataException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function capture($transactionId, TransactionInterface $transaction = null)
    {
        if ($transaction && !$transaction->isServiceDataValid()) {
            throw new EntityInvalidDataException('Currency code not specified for given amount.');
        }

        $postData = $transaction ? [self::API_NAME_TRANSACTION => $transaction->getServiceFormData()] : [];
        $response = $this->httpClient->post("/services/v1/vault/transactions/{$transactionId}/capture.json", $postData);
        return $this->retrieveItem($response, self::API_NAME_TRANSACTION, $transaction);
    }

    /**
     * @param int $transactionId
     * @param \SubscribePro\Service\Transaction\TransactionInterface|null $transaction
     * @return \SubscribePro\Service\Transaction\TransactionInterface
     * @throws \SubscribePro\Exception\EntityInvalidDataException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function credit($transactionId, TransactionInterface $transaction = null)
    {
        if ($transaction && !$transaction->isServiceDataValid()) {
            throw new EntityInvalidDataException('Currency code not specified for given amount.');
        }

        $postData = $transaction ? [self::API_NAME_TRANSACTION => $transaction->getServiceFormData()] : [];
        $response = $this->httpClient->post("/services/v1/vault/transactions/{$transactionId}/credit.json", $postData);
        return $this->retrieveItem($response, self::API_NAME_TRANSACTION, $transaction);
    }

    /**
     * @param int $transactionId
     * @return \SubscribePro\Service\Transaction\TransactionInterface
     * @throws \SubscribePro\Exception\HttpException
     */
    public function void($transactionId)
    {
        $response = $this->httpClient->post("/services/v1/vault/transactions/{$transactionId}/void.json");
        return $this->retrieveItem($response, self::API_NAME_TRANSACTION);
    }
}
