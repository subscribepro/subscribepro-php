<?php

namespace SubscribePro\Service\Customer;

use SubscribePro\Service\DataObject;

class Customer extends DataObject implements CustomerInterface
{
    /**
     * @var string
     */
    protected $idField = self::ID;

    /**
     * @var array
     */
    protected $creatingFields = [
        self::EMAIL => true,
        self::MAGENTO_CUSTOMER_ID => false,
        self::MAGENTO_CUSTOMER_GROUP_ID => false,
        self::MAGENTO_WEBSITE_ID => false,
        self::CREATE_MAGENTO_CUSTOMER => false,
        self::FIRST_NAME => true,
        self::MIDDLE_NAME => false,
        self::LAST_NAME => true
    ];

    /**
     * @var array
     */
    protected $updatingFields = [
        self::EMAIL => false,
        self::MAGENTO_CUSTOMER_ID => false,
        self::MAGENTO_CUSTOMER_GROUP_ID => false,
        self::MAGENTO_WEBSITE_ID => false,
        self::EXTERNAL_VAULT_CUSTOMER_TOKEN => false,
        self::FIRST_NAME => false,
        self::MIDDLE_NAME => false,
        self::LAST_NAME => false
    ];

    /**
     * @return array
     */
    protected function getFormFields()
    {
        return $this->isNew() ? $this->creatingFields : $this->updatingFields;
    }

    /**
     * @return array
     */
    public function getFormData()
    {
        return array_intersect_key($this->data, $this->getFormFields());
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->checkRequiredFields($this->getFormFields());
    }

    /**
     * @param int|null $id
     * @return $this
     */
    public function setId($id)
    {
        $this->setData($this->idField, $id);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->getData(self::FIRST_NAME);
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName($firstName)
    {
        return $this->setData(self::FIRST_NAME, $firstName);
    }

    /**
     * @return string|null
     */
    public function getLastName()
    {
        return $this->getData(self::LAST_NAME);
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName($lastName)
    {
        return $this->setData(self::LAST_NAME, $lastName);
    }

    /**
     * @return string|null
     */
    public function getMiddleName()
    {
        return $this->getData(self::MIDDLE_NAME);
    }

    /**
     * @param string|null $middleName
     * @return $this
     */
    public function setMiddleName($middleName)
    {
        return $this->setData(self::MIDDLE_NAME, $middleName);
    }

    /**
     * @return string|null
     */
    public function getFullName()
    {
        return $this->getData(self::FULL_NAME);
    }

    /**
     * @return int|null
     */
    public function getMagentoCustomerId()
    {
        return $this->getData(self::MAGENTO_CUSTOMER_ID);
    }

    /**
     * @param int|null $magentoCustomerId
     * @return $this
     */
    public function setMagentoCustomerId($magentoCustomerId)
    {
        return $this->setData(self::MAGENTO_CUSTOMER_ID, $magentoCustomerId);
    }

    /**
     * @return int|null
     */
    public function getMagentoCustomerGroupId()
    {
        return $this->getData(self::MAGENTO_CUSTOMER_GROUP_ID);
    }

    /**
     * @param int|null $magentoCustomerGroupId
     * @return $this
     */
    public function setMagentoCustomerGroupId($magentoCustomerGroupId)
    {
        return $this->setData(self::MAGENTO_CUSTOMER_GROUP_ID, $magentoCustomerGroupId);
    }

    /**
     * @return int|null
     */
    public function getMagentoWebsiteId()
    {
        return $this->getData(self::MAGENTO_WEBSITE_ID);
    }

    /**
     * @param int|null $magentoWebsiteId
     * @return $this
     */
    public function setMagentoWebsiteId($magentoWebsiteId)
    {
        return $this->setData(self::MAGENTO_WEBSITE_ID, $magentoWebsiteId);
    }

    /**
     * @return bool
     */
    public function getCreateMagentoCustomer()
    {
        return $this->getData(self::CREATE_MAGENTO_CUSTOMER);
    }

    /**
     * @param bool $createMagentoCustomer
     * @return $this
     */
    public function setCreateMagentoCustomer($createMagentoCustomer)
    {
        return $this->setData(self::CREATE_MAGENTO_CUSTOMER, $createMagentoCustomer);
    }

    /**
     * @return string|null
     */
    public function getExternalVaultCustomerToken()
    {
        return $this->getData(self::EXTERNAL_VAULT_CUSTOMER_TOKEN);
    }

    /**
     * @param string|null $externalVaultCustomerToken
     * @return $this
     */
    public function setExternalVaultCustomerToken($externalVaultCustomerToken)
    {
        return $this->setData(self::EXTERNAL_VAULT_CUSTOMER_TOKEN, $externalVaultCustomerToken);
    }

    /**
     * @return int|null
     */
    public function getActiveSubscriptionCount()
    {
        return $this->getData(self::ACTIVE_SUBSCRIPTION_COUNT);
    }

    /**
     * @return int|null
     */
    public function getSubscriptionCount()
    {
        return $this->getData(self::SUBSCRIPTION_COUNT);
    }

    /**
     * @return int|null
     */
    public function getActiveSubscribedQty()
    {
        return $this->getData(self::ACTIVE_SUBSCRIBED_QTY);
    }

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getCreated($format = null)
    {
        return $this->getDatetimeData(self::CREATED, $format);
    }

    /**
     * @param string|null $format
     * @return string|null
     */
    public function getUpdated($format = null)
    {
        return $this->getDatetimeData(self::UPDATED, $format);
    }
}
