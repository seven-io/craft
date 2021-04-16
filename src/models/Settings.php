<?php
namespace Sms77\Craft\models;

use craft\base\Model;

class Settings extends Model {
    public $apiKey = '';
    public $from = '';

    public function rules() {
        return [
            [['apiKey'], 'required'],
            [['apiKey'], 'string', 'max' => 90],
            [['from'], 'string', 'max' => 16],
        ];
    }
}