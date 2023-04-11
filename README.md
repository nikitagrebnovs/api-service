# API application

This is a simple service for register user, login and receive exchange rates (BTC, LTC to EUR). Application using Laravel Sanctum authentication method for login and store authentication token. Firstly user register by email and password then login and receive token, with token send request for cryptocurrency rates. Token has lifetime 1 minute, can be changed in AuthController.

## Install

    composer install
    mv .env.example .env
    php artisan key:generate

## Run the app

    php artisan serve

# Services

## Registration

### Request

`POST /api/register`

    ["email", "passoword"]

### Response

    date: 11.04.23 00:23:30
    satus: true
    message: User Created Successfull

## Login

### Request

`POST /api/login`

    ["email", "passoword"]

### Response

    date: 11.04.23 00:30:29
    satus: true
    message: User logged in successfully
    token: 20|b1SALJKEUYel7bZFhibKYMzt39wvjtopjiKyJnjx

## Get cryptocurrency rates

### Request

`GET /cryptocurrency/rates`

### Response

    date: 11.04.23 00:33:11
    satus: true
    message: Cryptocurrency rates
    data: {"date":"11.04.2023 00:29:39","status":true,"message":"Cryptocurrency rates","data":{"currency":"EUR","rates":{"BTC":"27143.3","LTC":"85.94"}}}

