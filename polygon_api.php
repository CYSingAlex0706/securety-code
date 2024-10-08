<?php

require 'vendor/autoload.php';
$config = require 'config.php';

use GuzzleHttp\Client;

function getStockData($ticker) {
    global $config;
    $apiKey = $config['polygon_api_key'];
    $client = new Client();
    
    $yesterdayDate = date('Y-m-d', strtotime('-1 day'));
    
    $url = "https://api.polygon.io/v1/open-close/$ticker/$yesterdayDate?adjusted=true&apiKey=$apiKey";

    try {
        $response = $client->request('GET', $url);
        $data = json_decode($response->getBody(), true);
        return $data;
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

?>