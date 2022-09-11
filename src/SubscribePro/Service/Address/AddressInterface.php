<?php

namespace SubscribePro\Service\Address;

use SubscribePro\Service\DataInterface;

interface AddressInterface extends DataInterface
{
    /**
     * Data fields
     */
    public const ID = 'id';
    public const CUSTOMER_ID = 'customer_id';
    public const MAGENTO_ADDRESS_ID = 'magento_address_id';
    public const FIRST_NAME = 'first_name';
    public const MIDDLE_NAME = 'middle_name';
    public const LAST_NAME = 'last_name';
    public const COMPANY = 'company';
    public const STREET1 = 'street1';
    public const STREET2 = 'street2';
    public const STREET3 = 'street3';
    public const CITY = 'city';
    public const REGION = 'region';
    public const POSTCODE = 'postcode';
    public const COUNTRY = 'country';
    public const PHONE = 'phone';
    public const CREATED = 'created';
    public const UPDATED = 'updated';

    /**
     * @return mixed[]
     */
    public function getFormData();

    /**
     * @param int|null $id
     *
     * @return $this
     */
    public function setId($id);

    /**
     * @param bool $isNew
     *
     * @return bool
     */
    public function isAsChildValid($isNew);

    /**
     * @param bool $isNew
     *
     * @return mixed[]
     */
    public function getAsChildFormData($isNew);

    /**
     * @return string|null
     */
    public function getCustomerId();

    /**
     * @param string $customerId
     *
     * @return $this
     */
    public function setCustomerId($customerId);

    /**
     * @return string|null
     */
    public function getFirstName();

    /**
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName($firstName);

    /**
     * @return string|null
     */
    public function getLastName();

    /**
     * @param string $lastName
     *
     * @return $this
     */
    public function setLastName($lastName);

    /**
     * @return string|null
     */
    public function getMiddleName();

    /**
     * @param string|null $middleName
     *
     * @return $this
     */
    public function setMiddleName($middleName);

    /**
     * @return string|null
     */
    public function getMagentoAddressId();

    /**
     * @return string|null
     */
    public function getCompany();

    /**
     * @param string|null $company
     *
     * @return $this
     */
    public function setCompany($company);

    /**
     * @return string|null
     */
    public function getStreet1();

    /**
     * @param string|null $street1
     *
     * @return $this
     */
    public function setStreet1($street1);

    /**
     * @return string|null
     */
    public function getStreet2();

    /**
     * @param string|null $street2
     *
     * @return $this
     */
    public function setStreet2($street2);

    /**
     * @return string|null
     */
    public function getStreet3();

    /**
     * @param string|null $street3
     *
     * @return $this
     */
    public function setStreet3($street3);

    /**
     * @return string|null
     */
    public function getCity();

    /**
     * @param string $city
     *
     * @return $this
     */
    public function setCity($city);

    /**
     * @return string|null
     */
    public function getRegion();

    /**
     * @param string $region
     *
     * @return $this
     */
    public function setRegion($region);

    /**
     * @return string|null
     */
    public function getPostcode();

    /**
     * @param string $postcode
     *
     * @return $this
     */
    public function setPostcode($postcode);

    /**
     * @return string|null
     */
    public function getCountry();

    /**
     * @param string $country
     *
     * @return $this
     */
    public function setCountry($country);

    /**
     * @return string|null
     */
    public function getPhone();

    /**
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone($phone);

    /**
     * @param string|null $format
     *
     * @return string|null
     */
    public function getCreated($format = null);

    /**
     * @param string|null $format
     *
     * @return string|null
     */
    public function getUpdated($format = null);
}
