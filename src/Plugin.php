<?php
namespace Seven\Craft;

use Craft;
use craft\events\RegisterCpNavItemsEvent;
use craft\web\twig\variables\Cp;
use Seven\Craft\models\Settings;
use yii\base\Event;

class Plugin extends \craft\base\Plugin {
    public $hasCpSettings = true;

    public function init() {
        parent::init();

        $this->registerTranslations();
        $this->registerMenu();
    }

    private function registerTranslations() {
        Craft::$app->i18n->translations['seven*'] = [
            'allowOverrides' => true,
            'basePath' => $this->getBasePath() . '/translations',
            'class' => PhpMessageSource::class,
            'fileMap' => [
                'seven' => 'site',
                'seven-app' => 'app',
            ],
            'sourceLanguage' => 'en',
        ];
    }

    private function registerMenu() {
        Event::on(Cp::class, Cp::EVENT_REGISTER_CP_NAV_ITEMS,
            function (RegisterCpNavItemsEvent $event) {
                $event->navItems[] = [
                    'label' => 'seven ' . Craft::t('seven', 'SMS'),
                    'url' => 'seven/sms',
                ];

                $event->navItems[] = [
                    'label' => 'seven ' . Craft::t('seven', 'Voice'),
                    'url' => 'seven/voice',
                ];
            }
        );
    }

    protected function createSettingsModel() {
        return new Settings;
    }

    protected function settingsHtml() {
        return Craft::$app->getView()
            ->renderTemplate('seven/settings', ['settings' => $this->getSettings()]);
    }
}
