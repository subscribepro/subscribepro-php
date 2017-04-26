<?php

namespace SubscribePro\Service\Customer;

use SubscribePro\Exception\EntityInvalidDataException;
use SubscribePro\Service\AbstractService;
use SubscribePro\Exception\InvalidArgumentException;

/**
 * Config options for customer service:
 * - instance_name
 *   Specified class must implement \SubscribePro\Service\Customer\CustomerInterface interface
 *   Default value is \SubscribePro\Service\Customer\Customer
 *   @see \SubscribePro\Service\Customer\CustomerInterface
 */
class CustomerService extends AbstractService
{
    /**
     * Service name
     */
    const NAME = 'customer';

    const API_NAME_CUSTOMER = 'customer';
    const API_NAME_CUSTOMERS = 'customers';

    /**
     * @var string
     */
    protected $allowedFilters = [
        CustomerInterface::MAGENTO_CUSTOMER_ID,
        CustomerInterface::EMAIL,
        CustomerInterface::FIRST_NAME,
        CustomerInterface::LAST_NAME
    ];

    /**
     * @param array $customerData
     * @return \SubscribePro\Service\Customer\CustomerInterface
     */
    public function createCustomer(array $customerData = [])
    {
        return $this->dataFactory->create($customerData);
    }

    /**
     * @param \SubscribePro\Service\Customer\CustomerInterface $customer
     * @return \SubscribePro\Service\Customer\CustomerInterface
     * @throws \SubscribePro\Exception\EntityInvalidDataException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function saveCustomer(CustomerInterface $customer)
    {
        $url = $customer->isNew() ? '/services/v2/customer.json' : "/services/v2/customers/{$customer->getId()}.json";
        $response = $this->httpClient->post($url, [self::API_NAME_CUSTOMER => $customer->getFormData()]);
        return $this->retrieveItem($response, self::API_NAME_CUSTOMER, $customer);
    }

    /**
     * @param int $customerId
     * @return \SubscribePro\Service\Customer\CustomerInterface
     * @throws \SubscribePro\Exception\HttpException
     */
    public function loadCustomer($customerId)
    {
        $response = $this->httpClient->get("/services/v2/customers/{$customerId}.json");
        return $this->retrieveItem($response, self::API_NAME_CUSTOMER);
    }

    /**
     * Retrieve an array of all customers. Customers may be filtered.
     *  Available filters:
     * - magento_customer_id
     * - email
     * - first_name
     * - last_name
     *
     * @param array $filters
     * @return \SubscribePro\Service\Customer\CustomerInterface[]
     * @throws \SubscribePro\Exception\InvalidArgumentException
     * @throws \SubscribePro\Exception\HttpException
     */
    public function loadCustomers(array $filters = [])
    {
        $invalidFilters = array_diff_key($filters, array_flip($this->allowedFilters));
        if (!empty($invalidFilters)) {
            throw new InvalidArgumentException(
                'Only [' . implode(', ', $this->allowedFilters) . '] query filters are allowed.'
            );
        }

        $response = $this->httpClient->get('/services/v2/customers.json', $filters);
        return $this->retrieveItems($response, self::API_NAME_CUSTOMERS);
    }
}
