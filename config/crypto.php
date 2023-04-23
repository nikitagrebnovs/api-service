<?php

use Illuminate\Support\Facades\Facade;

return [
    /*
       |--------------------------------------------------------------------------
       | Available crypto pairs
       |--------------------------------------------------------------------------
       */

    'pairs' => [
        "BNBBUSD",
        "BTCBUSD",
        "ETHBUSD",
        "LTCBUSD",
        "TRXBUSD",
        "XRPBUSD",
        "BNBUSDT",
        "BTCUSDT",
        "ETHUSDT",
        "LTCUSDT",
        "TRXUSDT",
        "XRPUSDT",
        "BNBBTC",
        "ETHBTC",
        "LTCBTC",
        "TRXBTC",
        "XRPBTC",
        "LTCBNB",
        "TRXBNB",
        "XRPBNB",
    ],

    /*
      |--------------------------------------------------------------------------
      | Exchange actions
      |--------------------------------------------------------------------------
      */

    'exchangeActions' => ['buy', 'sell'],

    /*
      |--------------------------------------------------------------------------
      | Binance order book categories
      |--------------------------------------------------------------------------
      */

    'exchangeActionTypeInOrderBook' => [
        'buy' => 'asks',
        'sell' => 'bids'
    ]
];
