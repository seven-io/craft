<?php
namespace Sms77\Craft\controllers;

use Craft;
use craft\web\Controller;
use Exception;
use Sms77\Api\Params\SmsParams;
use Sms77\Api\Params\VoiceParams;
use Sms77\Craft\services\AbstractService;
use yii\web\Response as YiiResponse;

class MessageController extends Controller {
    public function actionSms(): YiiResponse {
        $this->requireAdmin();
        $this->requirePostRequest();

        $body = Craft::$app->request->bodyParams;
        $to = $this->getTo($body);

        if (count($to)) {
            $this->send('sms', [(new SmsParams)
                ->setText($body['text'])
                ->setDebug((bool)$body['debug'])
                ->setDelay($body['delay'] ?? null)
                ->setFlash((bool)$body['flash'])
                ->setForeignId($body['foreign_id'] ?? null)
                ->setJson((bool)$body['json'])
                ->setLabel($body['label'] ?? null)
                ->setNoReload((bool)$body['no_reload'])
                ->setPerformanceTracking((bool)$body['performance_tracking'])
                ->setTo(implode(',', array_unique($to)))]);
        }

        return $this->redirectBack();
    }

    private function getTo(array $body): array {
        $to = array_filter(explode(',', $body['to']));

        if (class_exists('craft\commerce\Plugin') && !count($to)) {
            $filterCountries = $body['filter_countries'] ?? [];
            $craft = craft\commerce\Plugin::getInstance();
            $Addresses = $craft->getAddresses();

            foreach ($craft->getCustomers()->getAllCustomers() as $c) {
                $a = $Addresses->getAddressById(
                    $c->primaryBillingAddressId ?? $c->primaryShippingAddressId);
                $phone = '' === ($a->phone ?? '') ? $a->alternativePhone : $a->phone;

                if (count($filterCountries)
                    && !in_array($a->countryId, $filterCountries, true)) {
                    $phone = '';
                }

                if ('' !== ($phone ?? '')) {
                    $to[] = $phone;
                }
            }
        }

        if (!count($to)) {
            Craft::$app->session->setError(
                Craft::t('sms77', 'No recipient(s) found for the given configuration.'));
        }

        return $to;
    }

    private function send(string $method, array $params): void {
        $errors = [];
        $notices = [];

        foreach ($params as $param) {
            try {
                $notices[] = json_encode(AbstractService::initClient()->$method($param));
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        if (count($errors)) {
            Craft::$app->session->setError(implode(PHP_EOL, $errors));
        }

        if (count($notices)) {
            Craft::$app->session->setNotice(implode(PHP_EOL, $notices));
        }
    }

    private function redirectBack(): YiiResponse {
        return $this->redirect($this->request->getReferrer());
    }

    public function actionVoice(): YiiResponse {
        $this->pre();

        $body = Craft::$app->request->bodyParams;
        $to = $this->getTo($body);

        if (count($to)) {
            $requests = [];

            foreach (array_unique($to) as $to) {
                $requests[] = (new VoiceParams)
                    ->setTo($to)
                    ->setText($body['text'])
                    ->setXml((bool)$body['xml'])
                    ->setJson((bool)$body['json']);
            }

            $this->send('voice', $requests);
        }

        return $this->redirectBack();
    }

    private function pre(): void {
        $this->requireAdmin();
        $this->requirePostRequest();
    }
}