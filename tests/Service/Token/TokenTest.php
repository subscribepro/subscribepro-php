<?php

namespace SubscribePro\Tests\Service\Token;

use SubscribePro\Service\Token\Token;
use SubscribePro\Service\Token\TokenInterface;

class TokenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SubscribePro\Service\Token\TokenInterface
     */
    protected $token;

    protected function setUp()
    {
        $this->token = new Token();
    }

    /**
     * @param array $data
     * @param bool $isValid
     * @dataProvider isValidDataProvider
     */
    public function testIsValid($data, $isValid)
    {
        $this->token->importData($data);
        $this->assertEquals($isValid, $this->token->isValid());
    }

    /**
     * @return array
     */
    public function isValidDataProvider()
    {
        return [
            'Not valid: empty data' => [
                'data' => [],
                'isValid' => false
            ],
            'Not valid: without month' => [
                'data' => [
                    TokenInterface::NUMBER => 11,
                    TokenInterface::YEAR => 2024,
                    TokenInterface::FIRST_NAME => 'name',
                    TokenInterface::LAST_NAME => 'surname',
                    TokenInterface::ADDRESS1 => 'address',
                    TokenInterface::CITY => 'city',
                    TokenInterface::COUNTRY => 'country',
                    TokenInterface::ZIP => 'zip',
                    TokenInterface::STATE => 'state',
                ],
                'isValid' => false
            ],
            'Not valid: without address' => [
                'data' => [
                    TokenInterface::NUMBER => 11,
                    TokenInterface::MONTH => 04,
                    TokenInterface::YEAR => 2024,
                    TokenInterface::FIRST_NAME => 'name',
                    TokenInterface::LAST_NAME => 'surname',
                    TokenInterface::CITY => 'city',
                    TokenInterface::COUNTRY => 'country',
                    TokenInterface::ZIP => 'zip',
                    TokenInterface::STATE => 'state',
                ],
                'isValid' => false
            ],
            'Not valid: without number' => [
                'data' => [
                    TokenInterface::MONTH => 04,
                    TokenInterface::YEAR => 2024,
                    TokenInterface::FIRST_NAME => 'name',
                    TokenInterface::LAST_NAME => 'surname',
                    TokenInterface::ADDRESS1 => 'address',
                    TokenInterface::CITY => 'city',
                    TokenInterface::COUNTRY => 'country',
                    TokenInterface::ZIP => 'zip',
                    TokenInterface::STATE => 'state',
                ],
                'isValid' => false
            ],
            'Not valid: without first name' => [
                'data' => [
                    TokenInterface::NUMBER => 11,
                    TokenInterface::MONTH => 04,
                    TokenInterface::YEAR => 2024,
                    TokenInterface::LAST_NAME => 'surname',
                    TokenInterface::ADDRESS1 => 'address',
                    TokenInterface::CITY => 'city',
                    TokenInterface::COUNTRY => 'country',
                    TokenInterface::ZIP => 'zip',
                    TokenInterface::STATE => 'state',
                ],
                'isValid' => false
            ],
            'Valid' => [
                'data' => [
                    TokenInterface::NUMBER => 11,
                    TokenInterface::MONTH => 04,
                    TokenInterface::YEAR => 2024,
                    TokenInterface::FIRST_NAME => 'name',
                    TokenInterface::LAST_NAME => 'surname',
                    TokenInterface::ADDRESS1 => 'address',
                    TokenInterface::CITY => 'city',
                    TokenInterface::COUNTRY => 'country',
                    TokenInterface::ZIP => 'zip',
                    TokenInterface::STATE => 'state',
                ],
                'isValid' => true
            ],
        ];
    }
    
    public function testGetFormData()
    {
        $data = [
            TokenInterface::TOKEN => 'token',
            TokenInterface::PAYMENT_METHOD_TYPE => 'type',
            TokenInterface::CARD_TYPE => 'card type',
            TokenInterface::NUMBER => '4111 1111 1111 1111',
            TokenInterface::LAST_FOUR_DIGITS => '1111',
            TokenInterface::FIRST_SIX_DIGITS => '411111',
            TokenInterface::VERIFICATION_VALUE => 123,
            TokenInterface::MONTH => '04',
            TokenInterface::YEAR => '2019',
            TokenInterface::FIRST_NAME => 'first name',
            TokenInterface::LAST_NAME => 'last name',
            TokenInterface::FULL_NAME => 'full name',
            TokenInterface::COMPANY => 'company',
            TokenInterface::ADDRESS1 => 'address',
            TokenInterface::ADDRESS2 => 'address',
            TokenInterface::CITY => 'city',
            TokenInterface::STATE => 'state',
            TokenInterface::ZIP => 'zip',
            TokenInterface::COUNTRY => 'country',
            TokenInterface::PHONE => 'phone',
            TokenInterface::ELIGIBLE_FOR_CARD_UPDATER => true,
            TokenInterface::STORAGE_STATE => 'ready',
            TokenInterface::TEST => 'test',
            TokenInterface::FINGERPRINT => 'fingerprint',
            TokenInterface::CREATED_AT => '2016-12-12',
            TokenInterface::UPDATED_AT => '2016-12-12',
        ];
        $expectedData = [
            TokenInterface::NUMBER => '4111 1111 1111 1111',
            TokenInterface::VERIFICATION_VALUE => 123,
            TokenInterface::MONTH => '04',
            TokenInterface::YEAR => '2019',
            TokenInterface::FIRST_NAME => 'first name',
            TokenInterface::LAST_NAME => 'last name',
            TokenInterface::COMPANY => 'company',
            TokenInterface::ADDRESS1 => 'address',
            TokenInterface::ADDRESS2 => 'address',
            TokenInterface::CITY => 'city',
            TokenInterface::STATE => 'state',
            TokenInterface::ZIP => 'zip',
            TokenInterface::COUNTRY => 'country',
            TokenInterface::PHONE => 'phone',
        ];
        
        $this->token->importData($data);
        $this->assertEquals($expectedData, $this->token->getFormData());
    }
}
