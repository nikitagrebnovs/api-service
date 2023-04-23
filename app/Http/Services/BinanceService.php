<?php

namespace App\Http\Services;


use GuzzleHttp\Client;

class BinanceService
{
    protected string $baseUrl = 'https://testnet.binance.vision/api/v3';

    public function calculatePrice(array $data): array
    {
        return $this->getPriceByOrder($this->getOrderBook($data['pair']), $data['amount'], $data['action']);
    }

    public function getOrderBook(string $symbol, int $limit = 1000): array
    {
        $response = $this->get($this->prepareUrl("/depth?symbol={$symbol}&limit={$limit}"));

        return (array)json_decode($response->getBody(), true);
    }

    public function getPriceByOrder(array $orderData, float $amount, string $action): array
    {
        $remainingAmount = $amount;
        $moneySpent = 0;

        $orderBookAction = config('crypto.exchangeActionTypeInOrderBook')[$action];

        foreach ($orderData[$orderBookAction] as $loop => $order) {
            $price = $order[0];
            $orderQuantity = $order[1];

            if ($remainingAmount == 0) {
                break;
            }

            if ($orderQuantity < $remainingAmount) {
                $remainingAmount = $remainingAmount - $orderQuantity;
                $moneySpent += $orderQuantity * $price;
            } elseif ($orderQuantity > $remainingAmount) {
                $orderQuantity = $remainingAmount;
                $remainingAmount = 0;
                $moneySpent += $orderQuantity * $price;
            }

            if (count($orderData[$orderBookAction]) == $loop + 1 && $remainingAmount) {
                $availableToBuy = $amount - $remainingAmount;

                return [
                    'amount' => $amount,
                    "available_amount" => $amount - $remainingAmount,
                    'status' => 'false',
                    'message' => "Amount is higher than order book available amount. Available to {$action}: {$availableToBuy}", 'css-status' => 'danger'
                ];
            }
        }

        $price = round($moneySpent / $amount, 8);
        $cost = round($price * $amount, 8);

        return [
            'amount' => $amount,
            "available_amount" => $amount - $remainingAmount,
            'status' => 'true', 'cost' => $cost,
            'message' => "Price: {$price} Will be spent: {$cost}", 'css-status' => 'success'
        ];
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
