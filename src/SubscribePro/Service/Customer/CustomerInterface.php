<?php

namespace SubscribePro\Service\Customer;

use SubscribePro\Service\DataInterface;

interface CustomerInterface extends DataInterface
{
    /**
     * Data fields
     */
    const ID = 'id';
    const EMAIL = 'email';
    const MAGENTO_CUSTOMER_ID = 'magento_customer_id';
    const MAGENTO_CUSTOMER_GROUP_ID = 'magento_customer_group_id';
    const MAGENTO_WEBSITE_ID = 'magento_website_id';
    const CREATE_MAGENTO_CUSTOMER = 'create_magento_customer';
    const EXTERNAL_VAULT_CUSTOMER_TOKEN = 'external_vault_customer_token';
    const FIRST_NAME = 'first_name';
    const MIDDLE_NAME = 'middle_name';
    const LAST_NAME = 'last_name';
    const FULL_NAME = 'full_name';
    const ACTIVE_SUBSCRIPTION_COUNT = 'active_subscription_count';
    const SUBSCRIPTION_COUNT = 'subscription_count';
    const ACTIVE_SUBSCRIBED_QTY = 'active_subscribed_qty';
    const CREATED = 'created';
    const UPDATED = 'updated';


    /**
     * @return array
     */
    public function getFormData();

    /**
     * @return bool
     */
    public function isValid();

    /**
     * @param int|null $id
     * @return $this
     */
    public function setId($id);
    
    /**
     * @return string|null
     */
    public function getEmail();

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email);

    /**
     * @return string|null
     */
    public function getFirstName();

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName($firstName);

    /**
     * @return string|null
     */
    public function getLastName();

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName($lastName);

    /**
     * @return string|null
     */
    public function getMiddleName();

    /**
     * @param string|null $middleName
     * @return $this
     */
    public function setMiddleName($middleName);

    /**
     * @return string|null
     */
    public function getFullName();

    /**
     * @return int|null
     */
    public function getMagentoCustomerId();

    /**
     * @param int|null $magentoCustomerId
     * @return $this
     */
    public function setMagentoCustomerId($magentoCustomerId);

    /**
     * @return int|null
     */
    public function getMagentoCustomerGroupId();

    /**
     * @param int|null $magentoCustomerGroupId
     * @return $this
     */
    public function setMagentoCustomerGroupId($magentoCustomerGroupId);

    /**
     * @return int|null
     */
    public function getMagentoWebsiteId();

    /**
     * @param int|null $magentoWebsiteId
     * @return $this
     */
    public function setMagentoWebsiteId($magentoWebsiteId);

    /**
     * @return bool
     */
    public function getCreateMagentoCustomer();

    /**
     * @param bool $createMagentoCustomer
     * @return $this
     */
    public function setCreateMagentoCustomer($createMagentoCustomer);

    /**
     * @return string|null
     */
    public function getExternalVaultCustomerToken();

    /**
     * @param string|null $externalVaultCustomerToken
     * @return $this
     */
    public function setExternalVaultCustomerToken($externalVaultCustomerToken);

    /**
     * @return int|null
     */
    public function getActiveSubscriptionCount();

    /**
     * @return int|null
     */
    public function getSubscriptionCount();

    /**
     * @return int|null
     */
    public function getActiveSubscribedQty();

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getCreated($format = null);

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getUpdated($format = null);
}
