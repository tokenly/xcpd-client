<?php

namespace Tokenly\XCPDClient;


use Exception;
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
        $this->package('tokenly/xcpd-client', 'xcpd-client', __DIR__.'/../../');

        $this->app->bind('Tokenly\XCPDClient\Client', function($app) {
            $config = $app['config']['xcpd-client::xcpd'];
            $client = new Client($config['connection_string'], $config['rpc_user'], $config['rpc_password']);
            return $client;
        });
    }

}

