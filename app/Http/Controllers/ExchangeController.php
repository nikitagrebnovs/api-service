<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculatePriceRequest;
use App\Http\Services\BinanceService;
use App\Http\Services\CoinGateService;

class ExchangeController extends Controller
{
    public function __construct(private readonly CoinGateService $coinGateService, private readonly BinanceService $binanceService)
    {
    }

    public function index()
    {
        return response()->json([
            'date' => now()->format('d.m.Y H:i:s'),
            'status' => true,
            'message' => 'Cryptocurrency rates',
            'data' => $this->coinGateService->getPrices(),
        ]);
    }

    public function calculatePrice(CalculatePriceRequest $request): array
    {
        return $this->binanceService->calculatePrice($request->validated());
    }

    public function exchangeRates(): array
    {
        return $this->coinGateService->getPrices();
    }


}
