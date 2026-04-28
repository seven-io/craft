<p align="center">
  <img src="https://www.seven.io/wp-content/uploads/Logo.svg" width="250" alt="seven logo" />
</p>

<h1 align="center">seven SMS &amp; Voice for Craft CMS</h1>

<p align="center">
  Send SMS and text-to-speech messages from <a href="https://craftcms.com">Craft CMS</a> and <a href="https://craftcms.com/commerce">Craft Commerce</a> via the seven gateway.
</p>

<p align="center">
  <a href="LICENSE.md"><img src="https://img.shields.io/badge/License-MIT-teal.svg" alt="MIT License" /></a>
  <img src="https://img.shields.io/badge/Craft-3.1.5%2B-orange" alt="Craft 3.1.5+" />
  <img src="https://img.shields.io/badge/PHP-7.2%2B-purple" alt="PHP 7.2+" />
  <a href="https://packagist.org/packages/seven.io/craft"><img src="https://img.shields.io/packagist/v/seven.io/craft" alt="Packagist" /></a>
</p>

---

## Features

- **SMS Messaging** - Send single messages via control panel or programmatically; bulk-send to Craft Commerce customers
- **Voice Messaging** - Place text-to-speech calls, with XML mode and JSON-response toggle
- **Craft Commerce Integration** - Bulk messaging with country-based filtering and automatic phone-number extraction
- **Advanced Options** - Delay, flash SMS, performance tracking, custom labels, foreign IDs

## Prerequisites

- [Craft CMS](https://craftcms.com) 3.1.5 or newer
- (Optional) [Craft Commerce](https://craftcms.com/commerce) 2.x for bulk messaging
- A [seven account](https://www.seven.io/) with API key ([How to get your API key](https://help.seven.io/en/developer/where-do-i-find-my-api-key))

## Installation

### Composer

```bash
cd /path/to/craft-project
composer require seven.io/craft
./craft install/plugin seven
```

### Plugin Store

Install **seven** from the Craft Plugin Store.

## Configuration

Open **Settings > seven** in the Craft control panel:

| Field | Description |
|-------|-------------|
| API Key | Your seven API key (required) |
| From | Default sender ID. Up to 16 characters |

## Usage

### Control Panel

- **seven SMS** - Send single or bulk SMS
- **seven Voice** - Place single or bulk voice calls

For bulk Commerce messaging, leave the recipient field empty and pick countries to filter.

### Programmatic SMS

```php
use Seven\Craft\Plugin;

$sms = Plugin::getInstance()->getSms();
$sms->params
    ->setTo('+4901234567890')
    ->setText('Your message')
    ->setFrom('YourCompany')
    ->setDelay('2024-12-31 23:59')
    ->setFlash(true)
    ->setLabel('campaign-2024')
    ->setPerformanceTracking(true);

$success = $sms->send();
```

### Programmatic Voice

```php
use Seven\Craft\Plugin;

$voice = Plugin::getInstance()->getVoice();
$voice->params
    ->setTo('+4901234567890')
    ->setText('Hello there')
    ->setFrom('YourCompany')
    ->setXml(false)
    ->setJson(true);

$success = $voice->send();
```

### Available parameters

**SMS** (`SmsParams`): `setTo`, `setText`, `setFrom`, `setDelay`, `setFlash`, `setForeignId`, `setJson`, `setLabel`, `setPerformanceTracking`

**Voice** (`VoiceParams`): `setTo`, `setText`, `setFrom`, `setXml`, `setJson`

### Error handling

`send()` returns `true` for success (API response `100`) or `false` on failure. Detailed errors are logged via Craft's error handler.

## Support

Need help? Feel free to [contact us](https://www.seven.io/en/company/contact/) or [open an issue](https://github.com/seven-io/craft/issues).

## License

[MIT](LICENSE.md)
