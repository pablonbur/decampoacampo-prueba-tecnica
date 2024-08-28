<?php
class ResponseHelper
{
    public static function handleResponse($data): void
    {
        switch (true) {
            case $data instanceof PDOException:
                ResponseHelper::respondWithJson(false, 500, 'Error en la base de datos', $data);
                break;
            case empty($data):
                ResponseHelper::respondWithJson(false, 404, 'No hay resultados', []);
                break;
            default:
                ResponseHelper::respondWithJson(true, 200, '', $data);
                break;
        }
    }

    public static function respondWithJson(bool $success, int $statusCode, string $message = '', $data = null): void
    {
        http_response_code($statusCode);
        $response = [
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
