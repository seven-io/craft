<?php
namespace Sms77\Craft\traits;

use Sms77\Craft\services\SmsService;
use Sms77\Craft\services\VoiceService;

trait PluginComponentsTrait {
    public function registerComponents(): void {
        $this->setComponents([
            'sms' => SmsService::class,
            'voice' => VoiceService::class,
        ]);
    }

    public function getSms(): SmsService {
        return $this->get('sms');
    }

    public function getVoice(): VoiceService {
        return $this->get('voice');
    }
}