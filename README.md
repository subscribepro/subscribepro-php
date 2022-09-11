Subscribe Pro PHP Client
========================

[![Latest Stable Version](https://poser.pugx.org/subscribepro/subscribepro-php/v/stable)](https://packagist.org/packages/subscribepro/subscribepro-php)
[![Total Downloads](https://poser.pugx.org/subscribepro/subscribepro-php/downloads)](https://packagist.org/packages/subscribepro/subscribepro-php)
[![Latest Unstable Version](https://poser.pugx.org/subscribepro/subscribepro-php/v/unstable)](https://packagist.org/packages/subscribepro/subscribepro-php)
[![License](https://poser.pugx.org/subscribepro/subscribepro-php/license)](https://packagist.org/packages/subscribepro/subscribepro-php)

This is our PHP client library for accessing the Subscribe Pro REST API.  Our API documentation is available at https://platform.subscribepro.com/docs/rest.

To learn more about Subscribe Pro you can visit us at https://www.subscribepro.com/.

## Composer

You can install our PHP client via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require subscribepro/subscribepro-php
```

To use the PHP client, use Composer's [autoload](https://getcomposer.org/doc/00-intro.md#autoloading):

```php
require_once('vendor/autoload.php');
```

## Clean up code style

With `php-cs-fixer` v3.x installed, run this in project folder:

```bash
php-cs-fixer fix
```


## Running the tests

With dev dependencies installed via composer (these will install phpunit >= 9.5), run this:

```bash
vendor/bin/phpunit
```

## Getting Started

Simple usage looks like (example fetching a list of products):

```php
<?php

use SubscribePro\Sdk;

// Set credentials
$clientId     = 'XXXX';
$clientSecret = 'XXXX';

// Set log message format
$messageFormat = "SUBSCRIBE PRO REST API Call: {method} - {uri}\nRequest body: {req_body}\n{code} {phrase}\nResponse body: {res_body}\n{error}\n";

// Create SDK object
// Setup with Platform API base url and credentials from Magento config
$sdk = new Sdk([
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'logging_enable' => true,
    'logging_file_name' => 'var/log/subscribe_pro_api.log',
    'logging_message_format' => $messageFormat,
    'api_request_timeout' => 60,
]);

$products = $sdk
    ->getProductService()
    ->loadProducts(['sku' => 'SOME-EXAMPLE-SKU']);

```
