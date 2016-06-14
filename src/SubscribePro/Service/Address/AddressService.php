<?php

namespace SubscribePro\Service\Address;

use SubscribePro\Sdk;
use SubscribePro\Service\AbstractService;
use SubscribePro\Exception\InvalidArgumentException;

class AddressService extends AbstractService
{
    /**
     * Service name
     */
    const NAME = 'address';

    const API_NAME_ADDRESS = 'address';
    const API_NAME_ADDRESSES = 'addresses';

    /**
     * @param \SubscribePro\Sdk $sdk
     * @return \SubscribePro\Service\DataFactoryInterface
     */
    protected function createDataFactory(Sdk $sdk)
    {
        return new AddressFactory(
            $this->getConfigValue(self::CONFIG_INSTANCE_NAME, '\SubscribePro\Service\Address\Address')
        );
    }

    /**
     * @param array $addressData
     * @return \SubscribePro\Service\Address\AddressInterface
     */
    public function createAddress(array $addressData = [])
    {
        return $this->dataFactory->create($addressData);
    }

    /**
     * @param int $addressId
     * @return \SubscribePro\Service\Address\AddressInterface
     * @throws \SubscribePro\Exception\HttpException
     */
    public function loadAddress($addressId)
    {
        $response = $this->httpClient->get("/services/v2/addresses/{$addressId}.json");
        return $this->retrieveItem($response, self::API_NAME_ADDRESS);
    }

    /**
     * @param \SubscribePro\Service\Address\AddressInterface $address
     * @return \SubscribePro\Service\Address\AddressInterface
     * @throws \SubscribePro\Exception\HttpException
     */
    public function saveAddress(AddressInterface $address)
    {
        if (!$address->isValid()) {
            throw new InvalidArgumentException('Not all required fields are set.');
        }

        $url = $address->isNew() ? '/services/v2/address.json' : "/services/v2/addresses/{$address->getId()}.json";
        $response = $this->httpClient->post($url, [self::API_NAME_ADDRESS => $address->getFormData()]);
        return $this->retrieveItem($response, self::API_NAME_ADDRESS, $address);
    }

    /**
     * @param \SubscribePro\Service\Address\AddressInterface $address
     * @return \SubscribePro\Service\Address\AddressInterface
     * @throws \SubscribePro\Exception\InvalidArgumentException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function findOrSave($address)
    {
        if (!$address->isValid()) {
            throw new InvalidArgumentException('Not all required fields are set.');
        }

        $response = $this->httpClient->post('/services/v2/address/find-or-create.json', [self::API_NAME_ADDRESS => $address->getFormData()]);
        return $this->retrieveItem($response, self::API_NAME_ADDRESS, $address);
    }

    /**
     * @param int|null $customerId
     * @return \SubscribePro\Service\Address\AddressInterface[]
     * @throws \SubscribePro\Exception\HttpException
     */
    public function loadAddresses($customerId = null)
    {
        $params = $customerId ? [AddressInterface::CUSTOMER_ID => $customerId] : [];
        $response = $this->httpClient->get('/services/v2/addresses.json', $params);
        return $this->retrieveItems($response, self::API_NAME_ADDRESSES);
    }
}
