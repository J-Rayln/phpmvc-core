<?php

namespace JonathanRayln\Core\Exceptions;

class NotFoundException extends \Exception
{
    protected $code = 404;
    protected $message = 'That page could not be found';
}