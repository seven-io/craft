# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Craft CMS plugin for sending SMS and voice (text-to-speech) messages via the seven.io API. It integrates with both Craft CMS (3.1.5+) and optionally Craft Commerce (2.x) for bulk messaging capabilities.

**Package**: `seven.io/craft`
**Namespace**: `Seven\Craft\`
**Dependencies**:
- Craft CMS 3.1.0+
- `sms77/api` package (seven.io API client)

## Installation & Setup

```bash
# Install plugin via Composer
composer require seven.io/craft

# Install via Craft CLI
./craft install/plugin seven
```

After installation, configure the API key via Settings → seven in the Craft control panel.

## Architecture

### Core Components

**Plugin Class** (`src/Plugin.php`)
- Main plugin entry point extending `craft\base\Plugin`
- Uses `PluginComponentsTrait` to register SMS and Voice services
- Registers translations and control panel navigation items
- Handles settings model and settings template rendering

**Services** (`src/services/`)
- `AbstractService`: Base class for all services
  - Initializes seven.io API client (`Sms77\Api\Client`)
  - Validates plugin settings on init
  - Disables service if settings invalid
  - Provides static `initClient()` method for manual client initialization
- `SmsService`: SMS sending functionality using `SmsParams`
- `VoiceService`: Voice call functionality using `VoiceParams`
- Services use fluent interface via public `$params` property

**Controllers** (`src/controllers/`)
- `MessageController`: Handles SMS and Voice message dispatching from control panel
  - `actionSms()`: Send SMS messages (requires admin, POST)
  - `actionVoice()`: Send voice messages (requires admin, POST)
  - `getTo()`: Recipient resolution - supports manual entry or Craft Commerce customer bulk sending with country filtering
  - Commerce integration: Extracts phone numbers from customer billing/shipping addresses

**Models** (`src/models/`)
- `Settings`: Plugin configuration (apiKey required, from optional, max 16 chars)

**Traits** (`src/traits/`)
- `PluginComponentsTrait`: Service registration and getter methods (`getSms()`, `getVoice()`)

**Routes** (`src/config/routes.php`)
- Maps control panel URLs to controller actions:
  - `seven/sms` → `seven/message/sms`
  - `seven/voice` → `seven/message/voice`

**Templates** (`src/templates/`)
- Twig templates for control panel UI (settings, SMS form, Voice form)

### Service Usage Pattern

Services are accessed via the plugin instance and use a fluent interface:

```php
use Seven\Craft\Plugin;

$instance = Plugin::getInstance();

// SMS
$sms = $instance->getSms();
$sms->params
    ->setTo('+4901234567890')
    ->setText('Message')
    ->setFrom('Sender');
$sms->send();

// Voice
$voice = $instance->getVoice();
$voice->params
    ->setTo('+4901234567890')
    ->setText('Message')
    ->setFrom('Sender');
$voice->send();
```

### Craft Commerce Integration

The `MessageController::getTo()` method automatically extracts phone numbers from all customers when:
- Craft Commerce plugin is installed
- No manual recipients specified
- Applies country filtering if `filter_countries` provided

Phone number resolution priority: `phone` field, falls back to `alternativePhone`.

## Key Implementation Details

- **Error Handling**: Services return `false` on failure, log exceptions via Craft's error handler
- **API Response Codes**: Success code is `100` for both SMS and voice
- **Settings Validation**: Services automatically disable if settings invalid, show user notices
- **API Client**: Uses `sms77/api` package, initialized with API key and 'Craft' sentinel
- **Translations**: Supports i18n via `Craft::t('seven', ...)`, includes German translations
- **Security**: Controller actions require admin privileges and POST requests
