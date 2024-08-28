<?php
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    protected function postJson($uri, array $data): TestResponse
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://app_container/api" . $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return new TestResponse($response, $httpCode);
    }

    protected function getJson($uri): TestResponse
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://app_container/api" . $uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return new TestResponse($response, $httpCode);
    }
}

class TestResponse
{
    private $response;
    private $httpCode;

    public function __construct($response, $httpCode)
    {
        $this->response = $response;
        $this->httpCode = $httpCode;
    }

    public function getContent()
    {
        return $this->response;
    }

    public function assertStatus($statusCode)
    {
        if ($this->httpCode !== $statusCode) {
            throw new Exception("Se esperó: $statusCode, pero el resultado fue: {$this->httpCode}");
        }
    }
}
