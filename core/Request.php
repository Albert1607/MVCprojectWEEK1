<?php

namespace Core;

class Request
{
    /**
     * Get the HTTP request method in uppercase (GET, POST, etc.)
     */
    public function getMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * Get the sanitized request URI path (without query string)
     */
    public function getPath(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($uri, '?');
        if ($position !== false) {
            $uri = substr($uri, 0, $position);
        }
        $uri = trim($uri, '/');
        return '/' . $uri;
    }

    /**
     * Get all sanitized body inputs (POST data)
     */
    public function getBody(): array
    {
        $body = [];
        if ($this->getMethod() === 'POST') {
            foreach ($_POST as $key => $value) {
                if (is_array($value)) {
                    $body[$key] = filter_var_array($value, FILTER_DEFAULT);
                } else {
                    $body[$key] = trim(htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'));
                }
            }
        }
        return $body;
    }

    /**
     * Get all sanitized query string parameters (GET data)
     */
    public function getQueryParams(): array
    {
        $params = [];
        foreach ($_GET as $key => $value) {
            if (is_array($value)) {
                $params[$key] = filter_var_array($value, FILTER_DEFAULT);
            } else {
                $params[$key] = trim(htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'));
            }
        }
        return $params;
    }

    /**
     * Retrieve a specific input value from POST, GET, or default
     */
    public function input(string $key, mixed $default = null): mixed
    {
        $body = $this->getBody();
        if (array_key_exists($key, $body)) {
            return $body[$key];
        }

        $query = $this->getQueryParams();
        if (array_key_exists($key, $query)) {
            return $query[$key];
        }

        return $default;
    }
}
