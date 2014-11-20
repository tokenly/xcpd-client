<?php

use Tokenly\XCPDClient\Client;
use \Exception;
use \PHPUnit_Framework_Assert as PHPUnit;

/*
*
* To run the second test, you must set these environment variables first
* export XCPD_CONNECTION_STRING="http://127.0.0.1:4000"
* export XCPD_RPC_USER="rpcusername"
* export XCPD_RPC_PASSWORD="rpcpassword"

*/
class RequestTest extends \PHPUnit_Framework_TestCase
{


    public function testBuildRequest() {
        $xcpd_client = new Client($this->CONNECTION_STRING, $this->RPC_USER, $this->RPC_PASSWORD);
        $request = $xcpd_client->buildRequest('get_block_info', ['block_index' => 312599]);

        PHPUnit::assertEquals('{"method":"get_block_info","params":{"block_index":312599},"jsonrpc":"2.0","id":0}', $request->getBody());

        // print $request."\n";
    } 


    public function testSendRequest() {
        if (!$this->CONNECTION_IS_SET) {
            $this->markTestIncomplete("Authorization credentials must be defined as environment vars.");
        }


        $xcpd_client = new Client($this->CONNECTION_STRING, $this->RPC_USER, $this->RPC_PASSWORD);
        $response_data = $xcpd_client->get_block_info(['block_index' => 312600]);

        // echo json_encode($response_data, 192)."\n";

        PHPUnit::assertNotEmpty($response_data);
        PHPUnit::assertEquals('312600', $response_data['block_index']);
    } 



    public function setup() {
        $this->CONNECTION_STRING = getenv('XCPD_CONNECTION_STRING') ?: 'http://localhost:4000';
        $this->RPC_USER = getenv('XCPD_RPC_USER') ?: null;
        $this->RPC_PASSWORD = getenv('XCPD_RPC_PASSWORD') ?: null;

        $this->CONNECTION_IS_SET = strlen($this->RPC_USER) ? true : false;
    }

}
