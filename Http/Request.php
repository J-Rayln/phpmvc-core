<?php

namespace JonathanRayln\Core\Http;

class Request
{
    private array $routeParams = [];

    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');

        if ($position === false) {
            return $path;
        }

        return $path = substr($path, 0, $position);
    }

    public function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isGet()
    {
        return $this->method() === 'get';
    }

    public function isPost()
    {
        return $this->method() === 'post';
    }

    public function getBody()
    {
        $body = [];

        if ($this->method() === 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($this->method() === 'post') {
            foreach ($_POST as $key => $value) {
                if (is_array($value)) {
                    $body[$key] = $value;
                } else {
                    $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }

        return $body;
    }

    public function setRouteParams($params)
    {
        $this->routeParams = $params;

        return $this;
    }

    public function getRouteParams()
    {
        return $this->routeParams;
    }
}