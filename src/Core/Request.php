<?php

namespace Choco\Core;

class Request
{
    private array $params = [];

    // 🔵 POST data
    public function post(string $key = null)
    {
        if ($key) {
            return $_POST[$key] ?? null;
        }

        return $_POST;
    }

    // 🔵 GET data
    public function get(string $key = null)
    {
        if ($key) {
            return $_GET[$key] ?? null;
        }

        return $_GET;
    }

    // 🔵 URL params (/user/edit/{id})
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function param(int $index = null)
    {
        if ($index !== null) {
            return $this->params[$index] ?? null;
        }

        return $this->params;
    }

    public function method()
    {
        return $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
    }

    // 🔵 all inputs (utile debug)
    public function all()
    {
        return array_merge($_GET, $_POST, $this->params);
    }
}