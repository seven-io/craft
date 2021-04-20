<?php
namespace Sms77\Craft\services;

use Craft;
use Exception;
use Sms77\Api\Params\VoiceParams;

class VoiceService extends AbstractService {
    /** @var VoiceParams $params */
    public $params;

    /** {@inheritdoc} */
    public function init(): void {
        parent::init();

        $this->params = new VoiceParams();
        $this->params->setFrom($this->settings->from);
    }

    /** @return bool */
    public function send(): bool {
        if (!$this->enabled) {
            return false;
        }

        try {
            $res = $this->client->voice($this->params);
            $code = (int)($this->params->getJson()
                ? $res['success'] : explode(PHP_EOL, $res)[0]);

            if (100 !== $code) {
                throw new Exception(
                    Craft::t('sms77', 'Voice dispatch failed:') . " $code");
            }

            return true;

        } catch (Exception $e) {
            Craft::$app->getErrorHandler()->logException($e);
        }

        return false;
    }
}