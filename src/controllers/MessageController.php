<?php
namespace Sms77\Craft\controllers;

use Craft;
use craft\web\Controller;
use Sms77\Api\Params\SmsParams;
use Sms77\Craft\services\AbstractService;
use yii\web\Response as YiiResponse;

class MessageController extends Controller {
    public function actionSms(): YiiResponse {
        $this->requireAdmin();
        $this->requirePostRequest();

        $body = Craft::$app->request->bodyParams;
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

        if (count($to)) {
            $params = (new SmsParams)
                ->setText($body['text'])
                ->setDebug((bool)$body['debug'])
                ->setDelay($body['delay'] ?? null)
                ->setFlash((bool)$body['flash'])
                ->setForeignId($body['foreign_id'] ?? null)
                ->setJson((bool)$body['json'])
                ->setLabel($body['label'] ?? null)
                ->setNoReload((bool)$body['no_reload'])
                ->setPerformanceTracking((bool)$body['performance_tracking'])
                ->setTo(implode(',', array_unique($to)));

            try {
                Craft::$app->session->setNotice(
                    json_encode(AbstractService::initClient()->sms($params)));
            } catch (\Exception $e) {
                Craft::$app->session->setError($e->getMessage());
            }
        } else {
            Craft::$app->session->setError(
                Craft::t('sms77', 'No recipient(s) found for the given configuration.'));
        }

        return $this->redirect($this->request->getReferrer());
    }
}