<?php

namespace Utipd\XCPDClient;


use Exception;

/*
* Client
* A Counterparty client
*/
class Client
{

    public function __construct($connection_string, $rpc_user, $rpc_password) {
        $this->connection_string = $connection_string;
        $this->rpc_user = $rpc_user;
        $this->rpc_password = $rpc_password;
    }

    public function buildRequest($method, $params, \GuzzleHttp\Client $client=null) {
        // API requests are made via a HTTP POST request to /api/ (note the trailing slash), with JSON-encoded data passed as the POST body.
        // { "method": "METHOD NAME", "params": {"param1": "value1", "param2": "value2"}, "jsonrpc": "2.0", "id": 0 }
        $json_vars = ['method' => $method, 'params' => $params, 'jsonrpc' => '2.0', 'id' => 0, ];

        // if 
        if ($client === null) {
            $client = $this->buildClient();
        }

        return $client->createRequest('POST', '/api/', [
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => json_encode($json_vars),
        ]);
    }

    public function __call($name, $arguments) {
        // get the client
        $client = $this->buildClient();

        // build the request
        $request = $this->buildRequest($name, $arguments ? $arguments[0] : [], $client);

        // get the response
        $response = $client->send($request);

        // return json data
        $json = $response->json();

        // check for error
        if (isset($json['error'])) {
            $error = $json['error']['message'];
            if (isset($json['error']['data'])) {
                $error .= "\n".json_encode($json['error']['data'], 192);
            }
            throw new Exception($error, $json['error']['code']);
            
        }

        if (isset($json['result'])) {
            return $json['result'];
        }
        return $response;
    }


    protected function buildClient() {
        $client = new \GuzzleHttp\Client([
            'base_url' => $this->connection_string,
            'defaults' => [
                'auth'     => [$this->rpc_user, $this->rpc_password],
            ],
        ]);
        return $client;
    }
    

}

