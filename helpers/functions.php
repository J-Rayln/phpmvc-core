<?php

if (!function_exists('get_flash_messages')) {
    function get_flash_messages(string $key = 'success')
    {
        return \JonathanRayln\Core\Application::$app->session->getFlash($key);
    }
}