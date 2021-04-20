<?php
namespace Sms77\Craft;

use Craft;
use craft\events\RegisterCpNavItemsEvent;
use craft\web\twig\variables\Cp;
use Sms77\Craft\models\Settings;
use yii\base\Event;

class Plugin extends \craft\base\Plugin {
    public $hasCpSettings = true;

    public function init() {
        parent::init();

        $this->registerTranslations();
        $this->registerMenu();
    }

    private function registerTranslations() {
        Craft::$app->i18n->translations['sms77*'] = [
            'allowOverrides' => true,
            'basePath' => $this->getBasePath() . '/translations',
            'class' => PhpMessageSource::class,
            'fileMap' => [
                'sms77' => 'site',
                'sms77-app' => 'app',
            ],
            'sourceLanguage' => 'en',
        ];
    }

    private function registerMenu() {
        Event::on(Cp::class, Cp::EVENT_REGISTER_CP_NAV_ITEMS,
            function (RegisterCpNavItemsEvent $event) {
                $event->navItems[] = [
                    'label' => 'Sms77 ' . Craft::t('sms77', 'SMS'),
                    'url' => 'sms77/sms',
                ];

                $event->navItems[] = [
                    'label' => 'Sms77 ' . Craft::t('sms77', 'Voice'),
                    'url' => 'sms77/voice',
                ];
            }
        );
    }

    protected function createSettingsModel() {
        return new Settings;
    }

    protected function settingsHtml() {
        return Craft::$app->getView()
            ->renderTemplate('sms77/settings', ['settings' => $this->getSettings()]);
    }
}