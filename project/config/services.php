<?php

$config = require_once 'config.php';

return $config + [

    //Commands
    App\Commands\CalculateCommissionsCommand::class => DI\create()
        ->constructor(DI\get(App\Components\CommissionsCalculator\CommissionsCalculatorServiceInterface::class)),

    //CommissionsCalculatorService
    App\Components\CommissionsCalculator\CommissionsCalculatorServiceInterface::class => DI\create(App\Components\CommissionsCalculator\Services\CommissionsCalculatorService::class)
        ->constructor(
            DI\get('calculator.options'),
            DI\get(App\Components\CommissionsCalculator\Services\BinServiceInterface::class),
            DI\get(App\Components\CommissionsCalculator\Services\ExchangeRatesServiceInterface::class),
        ),

    App\Components\CommissionsCalculator\Services\BinServiceInterface::class => DI\create(App\Components\CommissionsCalculator\Infrastructure\BinListServiceAdapter::class)
        ->constructor(DI\get(App\Components\BinList\BinListServiceInterface::class)),

    App\Components\CommissionsCalculator\Services\ExchangeRatesServiceInterface::class => Di\create(App\Components\CommissionsCalculator\Infrastructure\ExchangeRatesApiAdapter::class)
        ->constructor(DI\get(App\Components\ExchangeRatesApi\ExchangeRatesApiServiceInterface::class)),

    //BinListService
    App\Components\BinList\BinListServiceInterface::class => DI\get(App\Components\BinList\Services\BinListService::class),

    App\Components\BinList\Services\BinListService::class => DI\create()
        ->constructor(DI\get(App\Components\BinList\Services\APIGatewayInterface::class)),

    App\Components\BinList\Services\APIGatewayInterface::class => DI\create(App\Components\BinList\Infrastructure\APIGateway::class)
        ->constructor(DI\get('binlist.url'), DI\get('binlist.timeout')),

    //ExchangeRatesApiService
    App\Components\ExchangeRatesApi\ExchangeRatesApiServiceInterface::class => DI\get(App\Components\ExchangeRatesApi\Services\ExchangeRatesApiService::class),

    App\Components\ExchangeRatesApi\Services\ExchangeRatesApiService::class => Di\create()
        ->constructor(DI\get(App\Components\ExchangeRatesApi\Services\APIGatewayInterface::class)),

    App\Components\ExchangeRatesApi\Services\APIGatewayInterface::class => DI\create(App\Components\ExchangeRatesApi\Infrastructure\APIGateway::class)
        ->constructor(DI\get('exchangeratesapi.url'), DI\get('exchangeratesapi.timeout'), DI\get('exchangeratesapi.access_key')),
    ];