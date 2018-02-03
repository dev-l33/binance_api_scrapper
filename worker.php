<?php
require 'vendor/autoload.php';

use Medoo\Medoo;
use GuzzleHttp\Client;

// Initialize
$database = new Medoo([
    'database_type' => 'mysql',
    'database_name' => 'glamston',
    'server' => 'localhost',
    'username' => 'root',
    'password' => 'root'
]);

$client = new Client([
    'base_uri' => 'https://api.binance.com',
    'timeout'  => 5.0,
]);

echo "Running Binace API every 30 seconds".PHP_EOL;

while(1) {

    echo "Requesting ticker".PHP_EOL;
    $response = $client->request('GET', '/api/v1/ticker/24hr');

    if ($response->getStatusCode() == 200) {
        $data = json_decode($response->getBody());
        foreach ($data as $item) {
            // Enjoy
            $result = $database->insert('binance_api', [
                    'symbol' => $item->symbol,
                    'price_change' => $item->priceChange,
                    'price_change_percent' => $item->priceChangePercent,
                    'last_price' => $item->lastPrice,
                    'last_quantity' => $item->lastQty,
                    'bid_price' => $item->bidPrice,
                    'bid_quantity' => $item->bidQty,
                    'ask_price' => $item->askPrice,
                    'open_price' => $item->openPrice,
                    'high_price' => $item->highPrice,
                    'low_price' => $item->lowPrice,
                    'volume' => $item->volume,
                    'timestamp' => date('Y-m-d G:i:s')
                ]);
        }
        echo "Saved successfully".PHP_EOL;
    } else {
        echo "Request Failed with code ".$response->getStatusCode();
    }
    
    sleep(30);
}