<?php

namespace App\Http\Controllers;

use App\Http\Services\CoinGateService;

class ExchangeController extends Controller
{
    public function __construct(private readonly CoinGateService $coinGateService)
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
}
