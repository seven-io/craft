<?php
namespace Sms77\Craft\services;

use Craft;
use Exception;
use Sms77\Api\Params\SmsParams;

class SmsService extends AbstractService {
    /** @var SmsParams $params */
    public $params;

    /** {@inheritdoc} */
    public function init(): void {
        parent::init();

        $this->params = new SmsParams();
        $this->params->setFrom($this->settings->from);
    }

    /** @return bool */
    public function send(): bool {
        if (!$this->enabled) {
            return false;
        }

        try {
            $res = $this->client->sms($this->params);
            $code = $this->params->getJson() ? $res['success'] : $res;

            if ('100' !== $code) {
                throw new Exception(
                    Craft::t('sms77', 'SMS dispatch failed:') . " $code");
            }

            return true;

        } catch (Exception $e) {
            Craft::$app->getErrorHandler()->logException($e);
        }

        return false;
    }
}