<?php
namespace Sms77\Craft\traits;

use Sms77\Craft\services\SmsService;

trait PluginComponentsTrait {
    public function registerComponents(): void {
        $this->setComponents(['sms' => SmsService::class]);
    }

    public function getSms(): SmsService {
        return $this->get('sms');
    }
}