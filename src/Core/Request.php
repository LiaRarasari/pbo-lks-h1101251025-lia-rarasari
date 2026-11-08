<?php
namespace App\Core;

class Request
{
    private string $method;
    private string $uri;
    private array $body;
    private array $queryParams;

    public function __construct()
    {
        $this->method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $parsedUrl = parse_url($requestUri);
        $this->uri = rawurldecode($parsedUrl['path'] ?? '/');
        
        $this->queryParams = $_GET;
        $this->body = $this->parseBody();
    }

    private function parseBody(): array
    {
        if (in_array($this->method, ['POST', 'PUT', 'PATCH'])) {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                return $data;
            }
            return $_POST;
        }
        return [];
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }
}