<?php
namespace Seven\Craft\services;

use Craft;
use craft\base\Component;
use Exception;
use Sms77\Api\Client;
use Seven\Craft\models\Settings;
use Seven\Craft\Plugin;

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
                    Craft::t('seven', 'Please configure the seven settings.'));
            }

            Craft::error(Craft::t('seven',
                'Missing settings: Please head to the settings page to correct them.'));

            $this->enabled = false;
        }

        $this->client = self::initClient($this->settings->apiKey);
    }

    /**
     * @param string|null $apiKey
     * @return Client
     * @throws Exception
     */
    public static function initClient(string $apiKey = null): Client {
        if (!$apiKey) $apiKey = Plugin::getInstance()->getSettings()->apiKey;

        return new Client($apiKey, 'Craft');
    }
}
