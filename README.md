The Counterparty Client component for Tokenly.


[![Build Status](https://travis-ci.org/tokenly/xcpd-client.svg?branch=master)](https://travis-ci.org/tokenly/xcpd-client)

A simple PHP client for the Counterparty API.

### Add the Laravel package via composer

```
composer require tokenly/xcpd-client
```

## Usage with Laravel

The service provider will automatically be registered in a Laravel 5.5+ application.

### Set the environment variables

```
XCPD_CONNECTION_STRING="http://127.0.0.1:4000"
XCPD_RPC_USER="rpcusername"
XCPD_RPC_PASSWORD="rpcpassword"
```

### Usage Example with Laravel

```php
$xcpd_client = app('Tokenly\CounterpartyClient\CounterpartyClient');
$response_data = $xcpd_client->get_block_info(['block_index' => 312600]);
/*
{
    "messages_hash": "72573bd491fe99a1ec5dfbd6b50d90184c242685bcee840557c5c2dc3f43da5b",
    "difficulty": 18736441558.310238,
    "block_hash": "00000000000000001af96dce9fb5d8e095257955c79d0b41ca64a2d69e664858",
    "previous_block_hash": "000000000000000010498d6e36255306e82ff9d7602bde867cb1226cceffa8aa",
    "txlist_hash": "cd868428581392142fdaef896ece4ea338d4fa286db1b873ea192789ccfb3f91",
    "block_time": 1406404765,
    "ledger_hash": "f0dfb4db2151bc2c427cace36f921dd992d5f640b654acb44709f3d44820c014",
    "block_index": 312600
}
*/
```

