<?php

class Response
{
    public static function json(mixed $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    public static function abort(int $code, string $message = ''): void
    {
        http_response_code($code);
        echo "<h1>Error {$code}</h1><p>{$message}</p>";
        exit;
    }
}
