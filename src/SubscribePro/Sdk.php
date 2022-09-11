<?php

namespace SubscribePro;

use SubscribePro\Exception\BadMethodCallException;
use SubscribePro\Exception\InvalidArgumentException;
use SubscribePro\Service\ServiceFactoryResolver;
use SubscribePro\Tools\ToolFactory;
use SubscribePro\Utils\StringUtils;

/**
 * @method \SubscribePro\Service\Product\ProductService               getProductService()
 * @method \SubscribePro\Service\Customer\CustomerService             getCustomerService()
 * @method \SubscribePro\Service\Address\AddressService               getAddressService()
 * @method \SubscribePro\Service\Subscription\SubscriptionService     getSubscriptionService()
 * @method \SubscribePro\Service\PaymentProfile\PaymentProfileService getPaymentProfileService()
 * @method \SubscribePro\Service\Transaction\TransactionService       getTransactionService()
 * @method \SubscribePro\Service\Token\TokenService                   getTokenService()
 * @method \SubscribePro\Service\Webhook\WebhookService               getWebhookService()
 * @method \SubscribePro\Tools\Report                                 getReportTool()
 * @method \SubscribePro\Tools\Config                                 getConfigTool()
 * @method \SubscribePro\Tools\Oauth                                  getOauthTool()
 *
 * @codeCoverageIgnore
 */
class Sdk
{
    use StringUtils;

    /**
     * The name of the environment variable that contains the client ID
     *
     * @const string
     */
    public const CLIENT_ID_ENV_NAME = 'SUBSCRIBEPRO_CLIENT_ID';

    /**
     * The name of the environment variable that contains the client secret
     *
     * @const string
     */
    public const CLIENT_SECRET_ENV_NAME = 'SUBSCRIBEPRO_CLIENT_SECRET';

    /**
     * @var \SubscribePro\Service\ServiceFactoryResolver
     */
    protected $serviceFactoryResolver;

    /**
     * @var \SubscribePro\Tools\ToolFactory
     */
    protected $toolFactory;

    /**
     * @var \SubscribePro\Http
     */
    protected $http;

    /**
     * @var array
     */
    protected $services = [];

    /**
     * @var array
     */
    protected $tools = [];

    /**
     * Config options:
     * - client_id
     * - client_secret
     * - base_url
     * - logging_enable
     *   default value false
     * - logging_level
     *   default value @see \Psr\Log\LogLevel::INFO
     * - logging_file_name
     *   default value @see \SubscribePro\Http::DEFAULT_LOG_FILE_NAME
     * - logging_line_format
     *   default value  @see \SubscribePro\Http::DEFAULT_LOG_LINE_FORMAT
     * - logging_message_format
     *   default value @see \SubscribePro\Http::DEFAULT_LOG_MESSAGE_FORMAT
     * - <service_name>
     *   Config options for specified service
     *
     * @param array $config
     *
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function __construct(array $config = [])
    {
        $config = array_merge([
            'client_id' => getenv(static::CLIENT_ID_ENV_NAME),
            'client_secret' => getenv(static::CLIENT_SECRET_ENV_NAME),
            'base_url' => null,
            'logging_enable' => false,
            'logging_level' => null,
            'logging_file_name' => null,
            'logging_line_format' => null,
            'logging_message_format' => null,
            'api_request_timeout' => 30,
        ], $config);

        if (!$config['client_id']) {
            throw new InvalidArgumentException('Required "client_id" key is not supplied in config and could not find fallback environment variable "' . static::CLIENT_ID_ENV_NAME . '"');
        }
        if (!$config['client_secret']) {
            throw new InvalidArgumentException('Required "client_secret" key is not supplied in config and could not find fallback environment variable "' . static::CLIENT_SECRET_ENV_NAME . '"');
        }

        $app = new App($config['client_id'], $config['client_secret']);
        unset($config['client_id']);
        unset($config['client_secret']);

        $this->http = new Http($app, $config['api_request_timeout'], $config['base_url']);
        unset($config['base_url']);

        if ($config['logging_enable']) {
            $this->http->addDefaultLogger(
                $config['logging_file_name'],
                $config['logging_line_format'],
                $config['logging_message_format'],
                $config['logging_level']
            );
        }
        unset($config['logging_enable']);
        unset($config['logging_level']);
        unset($config['logging_file_name']);
        unset($config['logging_line_format']);
        unset($config['logging_message_format']);

        $this->serviceFactoryResolver = new ServiceFactoryResolver($this->http, $config);
        $this->toolFactory = new ToolFactory($this->http);
    }

    /**
     * @return \SubscribePro\Http
     */
    public function getHttp()
    {
        return $this->http;
    }

    /**
     * Get service by name
     *
     * @param string $name
     *
     * @return \SubscribePro\Service\AbstractService
     *
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function getService($name)
    {
        if (!isset($this->services[$name])) {
            $this->services[$name] = $this->createService($name);
        }

        return $this->services[$name];
    }

    /**
     * @param string $name
     *
     * @return \SubscribePro\Service\AbstractService
     *
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    private function createService($name)
    {
        return $this->serviceFactoryResolver->getServiceFactory($name)->create();
    }

    /**
     * Get tool by name
     *
     * @param string $name
     *
     * @return \SubscribePro\Tools\AbstractTool
     *
     * @throws \SubscribePro\Exception\InvalidArgumentException
     */
    public function getTool($name)
    {
        if (!isset($this->tools[$name])) {
            $this->tools[$name] = $this->createTool($name);
        }

        return $this->tools[$name];
    }

    /**
     * @param $name
     *
     * @return \SubscribePro\Tools\AbstractTool
     */
    private function createTool($name)
    {
        return $this->toolFactory->create($name);
    }

    /**
     * @param string $method
     * @param array  $args
     *
     * @return \SubscribePro\Service\AbstractService|\SubscribePro\Tools\AbstractTool
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $args)
    {
        if ('get' === substr($method, 0, 3) && 'Service' === substr($method, -7)) {
            return $this->getService($this->underscore(substr($method, 3, -7)));
        }

        if ('get' === substr($method, 0, 3) && 'Tool' === substr($method, -4)) {
            return $this->getTool($this->underscore(substr($method, 3, -4)));
        }

        throw new BadMethodCallException("Method {$method} does not exist.");
    }
}
