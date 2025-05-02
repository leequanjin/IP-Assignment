<?php

class CurrencyConverter {

    private $base_url;
    private $access_key;
    private $endpoint = 'live';
    
    public function __construct() {
        $config = include 'config/config.php';
        $this->base_url = $config['currency_api']['base_url'];
        $this->access_key = $config['currency_api']['access_key'];
    }

    function getConversionRate($to) {
        $url = $this->base_url . $this->endpoint
                . '?access_key=' . $this->access_key
                . '&source=' . 'MYR'
                . '&currencies=' . $to;

        $response = file_get_contents($url);

        if ($response === false) {
            return false;
        }

        $data = json_decode($response, true); //When true, JSON objects will be returned as associative arrays
        return $data['quotes']['MYR' . $to] ?? false;
    }
}
