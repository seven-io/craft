[![Sms77.io Logo](https://www.sms77.io/wp-content/uploads/2019/07/sms77-Logo-400x79.png "Sms77.io Logo")](https://www.sms77.io)

# Official Craft [CMS](https://craftcms.com) + [Commerce](https://craftcms.com/commerce) Plugin

- Send SMS via control panel
- Send SMS via service
- Send Voice via control panel
- Send Voice via service
- Bulk SMS (Craft Commerce only)
- Bulk Voice (Craft Commerce only)

## Prerequisites

- [Craft CMS](https://craftcms.com) 3.1.5 or later
- An API key from [Sms77](https://www.sms77.io)
- (optionally) [Craft Commerce](https://craftcms.com/commerce) 2.x

## Installation

This plugin is installable via [Composer](https://getcomposer.org).

Open a terminal and execute the following commands:

```bash
# navigate to your project root
cd /var/www/craft

# retrieve the plugin source code via Composer
composer require sms77/craft

# install the plugin via Craft CLI
./craft install/plugin sms77
```

## Setup

After installing Sms77, go to `Settings → Sms77`, and enter your API key.

## Usage

How to send SMS:

```php
use Sms77\CraftCommerce\Plugin; // Use the plugin

$instance = Plugin::getInstance(); // init plugin

$sms = $instance->getSms(); // retrieve SMS service
    $sms->params // provides a fluent interface for method chaining
    ->setTo('+4901234567890, +456789012345') // required (recipient(s))
    ->setText('HI2U!') // required (message)
    ->setFrom('Craft'); // optional (caller id)
    $sms->send(); // dispatch

$voice = $instance->getVoice(); // retrieve Voice service
    $voice->params // provides a fluent interface for method chaining
    ->setTo('+4901234567890, +456789012345') // required (recipient(s))
    ->setText('HI2U!') // required (message)
    ->setFrom('Craft'); // optional (caller id)
    $voice->send(); // dispatch
```

### Support

Need help? Feel free to [contact us](https://www.sms77.io/en/company/contact/).

[![MIT](https://img.shields.io/badge/License-MIT-teal.svg)](./LICENSE.md)