<?php

namespace Tokenly\CounterpartyClient;

use Illuminate\Support\ServiceProvider;
use Tokenly\CounterpartyClient\CounterpartyClient;

/*
 * CounterpartyClientServiceProvider
 */
class CounterpartyClientServiceProvider extends ServiceProvider
{

    public function boot()
    {
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CounterpartyClient::class, function ($app) {
            $connection_string = env('XCPD_CONNECTION_STRING', 'http://localhost:4000');
            $client = new CounterpartyClient($connection_string, env('XCPD_RPC_USER'), env('XCPD_RPC_PASSWORD'));
            return $client;
        });
    }

}
