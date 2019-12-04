<?php

use Tokenly\CounterpartyClient\CounterpartyClient;
use \PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\TestCase;

/*
*
* To run the second test, you must set these environment variables first
* export XCPD_CONNECTION_STRING="http://127.0.0.1:4000"
* export XCPD_RPC_USER="rpcusername"
* export XCPD_RPC_PASSWORD="rpcpassword"

*/
class RequestTest extends TestCase
{


    public function testSendRequest() {
        if (!$this->CONNECTION_IS_SET) {
            $this->markTestIncomplete("Authorization credentials must be defined as environment vars.");
        }


        $xcpd_client = new CounterpartyClient($this->CONNECTION_STRING, $this->RPC_USER, $this->RPC_PASSWORD);
        $response_data = $xcpd_client->get_block_info(['block_index' => 312600]);

        // echo json_encode($response_data, 192)."\n";

        PHPUnit::assertNotEmpty($response_data);
        PHPUnit::assertEquals('312600', $response_data['block_index']);
        PHPUnit::assertEquals('00000000000000001af96dce9fb5d8e095257955c79d0b41ca64a2d69e664858', $response_data['block_hash']);
    } 



    public function setup() {
        $this->CONNECTION_STRING = getenv('XCPD_CONNECTION_STRING') ?: 'http://localhost:4000';
        $this->RPC_USER = getenv('XCPD_RPC_USER') ?: null;
        $this->RPC_PASSWORD = getenv('XCPD_RPC_PASSWORD') ?: null;

        $this->CONNECTION_IS_SET = strlen($this->RPC_USER) ? true : false;
    }

}
