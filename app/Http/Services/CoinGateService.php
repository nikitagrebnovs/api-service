<?php

namespace App\Http\Services;


use GuzzleHttp\Client;

class CoinGateService
{
    public string $currency = 'EUR';
    public array $cryptoCurrencies = ['BTC', 'LTC'];
    protected string $baseUrl = 'https://api.coingate.com/api/v2';

    public function getPrices(): array
    {
        $data['currency'] = $this->currency;

        foreach ($this->cryptoCurrencies as $cryptoCurrency) {
            $response = $this->get($this->prepareUrl("/rates/merchant/{$cryptoCurrency}/{$this->currency}"));

            $data['rates'][$cryptoCurrency] = (string)$response->getBody() ? (string)$response->getBody() : 'Not found';
        }

        return $data;
    }

    protected function prepareUrl(string $parameters): string
    {
        return $this->baseUrl . $parameters;
    }

    protected function get(string $url)
    {
        $client = new Client();

        return $client->request('GET', $url, [
            'headers' => [
                'accept' => 'text/plain',
            ],
        ]);
    }
}
