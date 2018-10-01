<?php

namespace common\components;

use Yii;
use yii\httpclient\Client;

class Dadata extends \yii\base\Component
{
    public $apiKey;
    public $secretKey;
    public $options = [];

    public $url = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/';

    public function init()
    {
        if (!$this->apiKey) {
            $this->apiKey = Yii::$app->params['dadata']['token'];
        }

        if (!$this->secretKey) {
            $this->secretKey = Yii::$app->params['dadata']['secret'];
        }

        $this->options = array_merge($this->optionsDefault(), $this->options);
    }

    public function party($query)
    {
        return $this->suggest('party', $query);
    }

    private function suggest($url, $query = null)
    {
        $client = new Client();

        $headers = [
            'Authorization' => 'Token ' . $this->apiKey,
            //'X-Secret' => $this->secretKey
        ];

        $this->addQuery($query);

        $response = $client->createRequest()
            ->setMethod('post')
            ->setFormat(Client::FORMAT_JSON)
            ->addHeaders($headers)
            ->setUrl($this->url . $url)
            ->setData($this->options)
            ->send();

        if ($response->isOk) {
            return $response->data['suggestions'];
        }

        return false;
    }

    private function addQuery($query)
    {
        $this->options['query'] = $query;
    }

    private function optionsDefault()
    {
        return [
            'count' => 5
        ];
    }
}