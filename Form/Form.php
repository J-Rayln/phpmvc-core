<?php

namespace JonathanRayln\Core\Form;

use JonathanRayln\Core\Base\Model;

class Form
{
    public static function begin(string $action, string $method): Form
    {
        echo sprintf('<form action="%s" method="%s">',
            $action, $method);

        return new Form();
    }

    public static function end(): void
    {
        echo '</form>';
    }

    public function input(Model $model, string $attribute): InputField
    {
        return new InputField($model, $attribute);
    }

    public function textarea(Model $model, string $attribute): TextareaField
    {
        return new TextareaField($model, $attribute);
    }
}