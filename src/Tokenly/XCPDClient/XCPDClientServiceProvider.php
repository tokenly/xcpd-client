<?php

namespace Tokenly\XCPDClient;


use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Tokenly\XCPDClient\Client;

/*
* XCPDClientServiceProvider
*/
class XCPDClientServiceProvider extends ServiceProvider
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
        $this->bindConfig();

        $this->app->bind('Tokenly\XCPDClient\Client', function($app) {
            $client = new Client(Config::get('xcpd-client.connection_string'), Config::get('xcpd-client.rpc_user'), Config::get('xcpd-client.rpc_password'));
            return $client;
        });
    }

    protected function bindConfig()
    {
        // simple config
        $config = [
            'xcpd-client.connection_string' => env('XCPD_CONNECTION_STRING', 'http://localhost:4000'),
            'xcpd-client.rpc_user'          => env('XCPD_RPC_USER', null),
            'xcpd-client.rpc_password'      => env('XCPD_RPC_PASSWORD', null),
        ];

        // set the laravel config
        Config::set($config);
    }

}

