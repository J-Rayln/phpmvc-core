<?php

if (!function_exists('get_flash_messages')) {
    function get_flash_messages(string $key = 'success')
    {
        return \JonathanRayln\Core\Application::$app->session->getFlash($key);
    }
}

if (!function_exists('url_is')) {
    function url_is(string $url): bool
    {
        $url = '/' . trim(strtolower($url), '/');

        return $url == \JonathanRayln\Core\Application::$app->request->getPath();
    }
}