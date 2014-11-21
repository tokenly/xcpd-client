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
        $this->package('tokenly/xcpd-client', 'xcpd-client', __DIR__.'/../../../');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Tokenly\XCPDClient\Client', function($app) {
            $config = $app['config']['xcpd-client::xcpd'];
            echo "\$config:\n".json_encode($config, 192)."\n";
            $client = new Client($config['connection_string'], $config['rpc_user'], $config['rpc_password']);
            return $client;
        });
    }

}

