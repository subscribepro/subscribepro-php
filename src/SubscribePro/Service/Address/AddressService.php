<?php

namespace SubscribePro\Service\Address;

use SubscribePro\Service\AbstractService;

/**
 * Config options for address service:
 * - instance_name
 *   Specified class must implement \SubscribePro\Service\Address\AddressInterface interface
 *   Default value is \SubscribePro\Service\Address\Address
 *
 *   @see \SubscribePro\Service\Address\AddressInterface
 *
 * @method \SubscribePro\Service\Address\AddressInterface   retrieveItem($response, $entityName, \SubscribePro\Service\DataInterface $item = null)
 * @method \SubscribePro\Service\Address\AddressInterface[] retrieveItems($response, $entitiesName)
 *
 * @property \SubscribePro\Service\Address\AddressFactory $dataFactory
 */
class AddressService extends AbstractService
{
    /**
     * Service name
     */
    public const NAME = 'address';

    public const API_NAME_ADDRESS = 'address';
    public const API_NAME_ADDRESSES = 'addresses';

    /**
     * @param array $addressData
     *
     * @return \SubscribePro\Service\Address\AddressInterface
     */
    public function createAddress(array $addressData = [])
    {
        return $this->dataFactory->create($addressData);
    }

    /**
     * @param int $addressId
     *
     * @return \SubscribePro\Service\Address\AddressInterface
     *
     * @throws \SubscribePro\Exception\HttpException
     */
    public function loadAddress($addressId)
    {
        $response = $this->httpClient->get("/services/v2/addresses/{$addressId}.json");

        return $this->retrieveItem($response, self::API_NAME_ADDRESS);
    }

    /**
     * @param \SubscribePro\Service\Address\AddressInterface $address
     *
     * @return \SubscribePro\Service\Address\AddressInterface
     *
     * @throws \SubscribePro\Exception\EntityInvalidDataException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function saveAddress(AddressInterface $address)
    {
        $url = $address->isNew() ? '/services/v2/address.json' : "/services/v2/addresses/{$address->getId()}.json";
        $response = $this->httpClient->post($url, [self::API_NAME_ADDRESS => $address->getFormData()]);

        return $this->retrieveItem($response, self::API_NAME_ADDRESS, $address);
    }

    /**
     * @param \SubscribePro\Service\Address\AddressInterface $address
     *
     * @return \SubscribePro\Service\Address\AddressInterface
     *
     * @throws \SubscribePro\Exception\EntityInvalidDataException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function findOrSave(AddressInterface $address)
    {
        $response = $this->httpClient->post('/services/v2/address/find-or-create.json', [self::API_NAME_ADDRESS => $address->getFormData()]);

        return $this->retrieveItem($response, self::API_NAME_ADDRESS, $address);
    }

    /**
     * @param int|null $customerId
     *
     * @return \SubscribePro\Service\Address\AddressInterface[]
     *
     * @throws \SubscribePro\Exception\HttpException
     */
    public function loadAddresses($customerId = null)
    {
        $params = $customerId ? [AddressInterface::CUSTOMER_ID => $customerId] : [];
        $response = $this->httpClient->get('/services/v2/addresses.json', $params);

        return $this->retrieveItems($response, self::API_NAME_ADDRESSES);
    }
}
