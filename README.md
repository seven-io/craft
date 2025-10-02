<img src="https://www.seven.io/wp-content/uploads/Logo.svg" width="250" />

# Official Craft [CMS](https://craftcms.com) + [Commerce](https://craftcms.com/commerce) Plugin

Send SMS and voice (text-to-speech) messages directly from your Craft CMS control panel or programmatically via service APIs.

## Features

- **SMS Messaging**
  - Send individual SMS via control panel
  - Programmatic SMS API with fluent interface
  - Bulk SMS to Craft Commerce customers
  - Advanced options: delay, flash SMS, performance tracking, custom labels

- **Voice Messaging**
  - Send individual voice calls via control panel
  - Programmatic voice API with fluent interface
  - Bulk voice calls to Craft Commerce customers
  - Text-to-speech with XML support

- **Craft Commerce Integration** (optional)
  - Bulk messaging to all customers
  - Country-based filtering
  - Automatic phone number extraction from customer addresses

## Prerequisites

- [Craft CMS](https://craftcms.com) 3.1.5 or later
- An API key from [seven.io](https://www.seven.io)
- (Optional) [Craft Commerce](https://craftcms.com/commerce) 2.x for bulk messaging features

## Installation

Install via [Composer](https://getcomposer.org):

```bash
# Navigate to your Craft project root
cd /path/to/your/craft-project

# Install the plugin
composer require seven.io/craft

# Install via Craft CLI
./craft install/plugin seven
```

Alternatively, install from the Craft Plugin Store.

## Configuration

1. Navigate to **Settings → seven** in your Craft control panel
2. Enter your seven.io API key (required)
3. Optionally set a default sender ID/from number (max 16 characters)

## Usage

### Control Panel

Access the plugin via the main navigation:
- **seven SMS** - Send individual or bulk SMS messages
- **seven Voice** - Send individual or bulk voice calls

For bulk messaging with Craft Commerce, leave the recipient field empty and optionally filter by country.

### Programmatic Usage

#### Send SMS

```php
use Seven\Craft\Plugin;

$instance = Plugin::getInstance();
$sms = $instance->getSms();

$sms->params
    ->setTo('+4901234567890')           // Required: recipient(s), comma-separated
    ->setText('Your message here')      // Required: message text
    ->setFrom('YourCompany')            // Optional: sender ID (max 16 chars)
    ->setDelay('2024-12-31 23:59')     // Optional: scheduled delivery
    ->setFlash(true)                    // Optional: flash SMS
    ->setLabel('campaign-2024')         // Optional: custom label
    ->setPerformanceTracking(true);     // Optional: enable tracking

$success = $sms->send(); // Returns true on success, false on failure
```

#### Send Voice Call

```php
use Seven\Craft\Plugin;

$instance = Plugin::getInstance();
$voice = $instance->getVoice();

$voice->params
    ->setTo('+4901234567890')           // Required: recipient(s), comma-separated
    ->setText('Your message here')      // Required: message text (TTS)
    ->setFrom('YourCompany')            // Optional: caller ID
    ->setXml(false)                     // Optional: XML mode
    ->setJson(true);                    // Optional: JSON response

$success = $voice->send(); // Returns true on success, false on failure
```

#### Available Parameters

**SMS Parameters** (via `SmsParams`):
- `setTo(string)` - Recipient phone number(s), comma-separated
- `setText(string)` - Message text
- `setFrom(string)` - Sender ID (alphanumeric, max 16 chars)
- `setDelay(string)` - Scheduled delivery timestamp
- `setFlash(bool)` - Send as flash SMS
- `setForeignId(string)` - Custom foreign ID
- `setJson(bool)` - JSON response format
- `setLabel(string)` - Custom label for tracking
- `setPerformanceTracking(bool)` - Enable performance tracking

**Voice Parameters** (via `VoiceParams`):
- `setTo(string)` - Recipient phone number(s), comma-separated
- `setText(string)` - Text-to-speech message
- `setFrom(string)` - Caller ID
- `setXml(bool)` - XML mode
- `setJson(bool)` - JSON response format

### Error Handling

The `send()` method returns a boolean:
- `true` - Message sent successfully (API response code 100)
- `false` - Sending failed (logged via Craft's error handler)

Check Craft logs for detailed error messages.

## Craft Commerce Bulk Messaging

When Craft Commerce is installed, you can send messages to all customers:

1. Navigate to **seven SMS** or **seven Voice**
2. Leave the recipient field empty
3. Optionally select countries to filter recipients
4. Enter your message and send

The plugin automatically extracts phone numbers from customer billing/shipping addresses.

## API Response Codes

- `100` - Success
- Other codes indicate errors (see [seven.io API documentation](https://www.seven.io/en/docs/gateway/http-api/) for details)

## Support

Need help? Contact us:
- Email: [support@seven.io](mailto:support@seven.io)
- Website: [seven.io/en/company/contact](https://www.seven.io/en/company/contact/)
- Issues: [GitHub Issues](https://github.com/seven-io/craft/issues)

## License

[![MIT](https://img.shields.io/badge/License-MIT-teal.svg)](LICENSE.md)
