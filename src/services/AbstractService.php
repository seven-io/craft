<?php
namespace Sms77\Craft\services;

use Craft;
use craft\base\Component;
use Sms77\Api\Client;
use Sms77\Craft\models\Settings;
use Sms77\Craft\Plugin;

abstract class AbstractService extends Component {
    /** @var Client $client */
    public $client;

    /** @var bool $enabled */
    public $enabled = true;

    /** @var Settings $settings */
    public $settings;

    /** {@inheritdoc} */
    public function init(): void {
        parent::init();

        $this->settings = Plugin::getInstance()->getSettings();

        if (!$this->settings->validate()) {
            if (!Craft::$app->getRequest()->getIsAjax()) {

                Craft::$app->getSession()->setNotice(
                    Craft::t('sms77', 'Please configure the Sms77 settings.'));
            }

            Craft::error(Craft::t('sms77',
                'Missing settings: Please head to the settings page to correct them.'));

            $this->enabled = false;
        }

        $this->client = self::initClient($this->settings->apiKey);
    }

    /**
     * @param null|string $apiKey
     * @return Client
     */
    public static function initClient($apiKey = null): Client {
        if (!$apiKey) {
            $apiKey = Plugin::getInstance()->getSettings()->apiKey;
        }

        return new Client($apiKey, 'Craft');
    }
}