<img src="https://www.seven.io/wp-content/uploads/Logo.svg" width="250" />


# Official Craft [CMS](https://craftcms.com) + [Commerce](https://craftcms.com/commerce) Plugin

- Send SMS via control panel
- Send SMS via service
- Send Voice via control panel
- Send Voice via service
- Bulk SMS (Craft Commerce only)
- Bulk Voice (Craft Commerce only)

## Prerequisites

- [Craft CMS](https://craftcms.com) 3.1.5 or later
- An API key from [seven](https://www.seven.io)
- (optionally) [Craft Commerce](https://craftcms.com/commerce) 2.x

## Installation

This plugin is installable via [Composer](https://getcomposer.org).

Open a terminal and execute the following commands:

```bash
# navigate to your project root
cd /var/www/craft

# retrieve the plugin source code via Composer
composer require seven.io/craft

# install the plugin via Craft CLI
./craft install/plugin seven
```

## Setup

After installing seven, go to `Settings → seven`, and enter your API key.

## Usage

How to send SMS:

```php
use Seven\Craft\Plugin; // Use the plugin

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

Need help? Feel free to [contact us](https://www.seven.io/en/company/contact/).

[![MIT](https://img.shields.io/badge/License-MIT-teal.svg)](LICENSE.md)
