<?php

namespace SubscribePro\Service\Transaction;

use SubscribePro\Sdk;
use SubscribePro\Service\AbstractService;
use SubscribePro\Service\Address\AddressInterface;
use SubscribePro\Exception\InvalidArgumentException;

class TransactionService extends AbstractService
{
    /**
     * Service name
     */
    const NAME = 'transaction';

    const API_NAME_TRANSACTION = 'transaction';

    /**
     * @param \SubscribePro\Sdk $sdk
     * @return \SubscribePro\Service\DataFactoryInterface
     */
    protected function createDataFactory(Sdk $sdk)
    {
        return new TransactionFactory(
            $this->getConfigValue(self::CONFIG_INSTANCE_NAME, '\SubscribePro\Service\Transaction\Transaction')
        );
    }

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
     * @throws \SubscribePro\Exception\InvalidArgumentException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function verifyProfile($paymentProfileId, TransactionInterface $transaction)
    {
        if (!$transaction->isVerifyDataValid()) {
            throw new InvalidArgumentException('Not all required fields are set.');
        }

        $response = $this->httpClient->post(
            "/services/v1/vault/paymentprofiles/{$paymentProfileId}/verify.json",
            [self::API_NAME_TRANSACTION => $transaction->getVerifyFormData()]
        );
        return $this->retrieveItem($response, self::API_NAME_TRANSACTION, $transaction);
    }

    /**
     * @param int $paymentProfileId
     * @param \SubscribePro\Service\Transaction\TransactionInterface $transaction
     * @return \SubscribePro\Service\Transaction\TransactionInterface
     * @throws \SubscribePro\Exception\InvalidArgumentException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function authorizeByProfile($paymentProfileId, TransactionInterface $transaction)
    {
        if (!$transaction->isValid()) {
            throw new InvalidArgumentException('Not all required fields are set.');
        }

        $response = $this->httpClient->post(
            "/services/v1/vault/paymentprofiles/{$paymentProfileId}/authorize.json",
            [self::API_NAME_TRANSACTION => $transaction->getFormData()]
        );
        return $this->retrieveItem($response, self::API_NAME_TRANSACTION, $transaction);
    }

    /**
     * @param int $paymentProfileId
     * @param \SubscribePro\Service\Transaction\TransactionInterface $transaction
     * @return \SubscribePro\Service\Transaction\TransactionInterface
     * @throws \SubscribePro\Exception\InvalidArgumentException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function purchaseByProfile($paymentProfileId, TransactionInterface $transaction)
    {
        if (!$transaction->isValid()) {
            throw new InvalidArgumentException('Not all required fields are set.');
        }

        $response = $this->httpClient->post(
            "/services/v1/vault/paymentprofiles/{$paymentProfileId}/purchase.json",
            [self::API_NAME_TRANSACTION => $transaction->getFormData()]
        );
        return $this->retrieveItem($response, self::API_NAME_TRANSACTION, $transaction);
    }

    /**
     * @param string $token
     * @param \SubscribePro\Service\Transaction\TransactionInterface $transaction
     * @param \SubscribePro\Service\Address\AddressInterface|null $address
     * @return \SubscribePro\Service\Token\TokenInterface
     * @throws \SubscribePro\Exception\InvalidArgumentException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function authorizeByToken($token, TransactionInterface $transaction, AddressInterface $address = null)
    {
        if (!$transaction->isTokenDataValid()) {
            throw new InvalidArgumentException('Not all required Transaction fields are set.');
        }

        if ($address && !$address->isAsChildValid(true)) {
            throw new InvalidArgumentException('Not all required Address fields are set.');
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
     * @return \SubscribePro\Service\Token\TokenInterface
     * @throws \SubscribePro\Exception\InvalidArgumentException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function purchaseByToken($token, TransactionInterface $transaction, AddressInterface $address = null)
    {
        if (!$transaction->isTokenDataValid()) {
            throw new InvalidArgumentException('Not all required Transaction fields are set.');
        }

        if ($address && !$address->isAsChildValid(true)) {
            throw new InvalidArgumentException('Not all required Address fields are set.');
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
     * @throws \SubscribePro\Exception\InvalidArgumentException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function capture($transactionId, TransactionInterface $transaction = null)
    {
        if ($transaction && !$transaction->isServiceDataValid()) {
            throw new InvalidArgumentException('Currency code not specified for given amount.');
        }

        $postData = $transaction ? [self::API_NAME_TRANSACTION => $transaction->getServiceFormData()] : [];
        $response = $this->httpClient->post("/services/v1/vault/transactions/{$transactionId}/capture.json", $postData);
        return $this->retrieveItem($response, self::API_NAME_TRANSACTION, $transaction);
    }

    /**
     * @param int $transactionId
     * @param \SubscribePro\Service\Transaction\TransactionInterface|null $transaction
     * @return \SubscribePro\Service\Transaction\TransactionInterface
     * @throws \SubscribePro\Exception\InvalidArgumentException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function credit($transactionId, TransactionInterface $transaction = null)
    {
        if ($transaction && !$transaction->isServiceDataValid()) {
            throw new InvalidArgumentException('Currency code not specified for given amount.');
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
