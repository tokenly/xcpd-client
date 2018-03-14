<?php

namespace Tokenly\CounterpartyClient;


use Closure;
use Exception;
use Requests;

/*
* CounterpartyClient
* A Counterparty client
*/
class CounterpartyClient
{

    public function __construct($connection_string, $rpc_user, $rpc_password) {
        $this->connection_string = $connection_string;
        $this->rpc_user = $rpc_user;
        $this->rpc_password = $rpc_password;
    }

    public function __call($name, $arguments) {
        $request_headers = ['Content-Type' => 'application/json'];
        $request_options = ['auth' => [$this->rpc_user, $this->rpc_password]];
        $params = $arguments ? $arguments[0] : [];

        // API requests are made via a HTTP POST request to /api/ (note the trailing slash), with JSON-encoded data passed as the POST body.
        // { "method": "METHOD NAME", "params": {"param1": "value1", "param2": "value2"}, "jsonrpc": "2.0", "id": 0 }
        $json_vars = ['method' => $name, 'params' => $params, 'jsonrpc' => '2.0', 'id' => 0, ];
        $json_vars_string = json_encode($json_vars);

        // call the request
        $response = $this->callRequest($this->connection_string.'/api/', $request_headers, $json_vars_string, 'POST', $request_options);

        // decode json
        $json = $this->decodeJsonFromResponse($response->body);

        // check for error
        if (substr($response->status_code, 0, 1) != '2') {
            if ($json and isset($json['message'])) {
                $error_string = $json['message'];
                $code = isset($json['code']) ? $json['code'] : 1;
                throw new Exception("{$response->status_code} error: {$error_string}".($code != 1 ? " ({$code})" : ''), $code);
            }
        }
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

        throw new Exception("Unexpected Response: ".$response->body, 1);
    }

    // ------------------------------------------------------------------------
    
    protected function decodeJsonFromResponse($response_body) {
        try {
            $json = json_decode($response_body, true);
        } catch (Exception $parse_json_exception) {
            // could not parse json
            throw new Exception("Unexpected response from server", 1);
        }

        return $json;
    }


    protected function callRequest($url, $headers, $request_params, $method, $request_options) {
        return Requests::request($url, $headers, $request_params, $method, $request_options);
    }
    

}

