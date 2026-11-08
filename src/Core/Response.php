<?php
namespace App\Core;

class Response
{
    public static function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    public static function success(mixed $data = null, string $message = 'Success', int $statusCode = 200): void
    {
        self::json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $data
        ], $statusCode);
    }

    public static function error(string $message = 'Error', int $statusCode = 400, mixed $errors = null): void
    {
        $payload = [
            'status'  => 'error',
            'message' => $message
        ];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        self::json($payload, $statusCode);
    }
}