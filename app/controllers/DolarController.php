<?php

require_once __DIR__ . '/../helpers/ResponseHelper.php';

class DolarController
{
    private string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = 'https://v6.exchangerate-api.com/v6/' . getenv('KEY') . '/latest/USD';
    }

    public function getRate(): void
    {
        $response = file_get_contents($this->apiUrl);
        $data = json_decode($response, true);

        if ($data['result'] === 'success') {
            ResponseHelper::respondWithJson(true, 'Cotización del dólar', $data['conversion_rates']['ARS']);
        }
    }
}