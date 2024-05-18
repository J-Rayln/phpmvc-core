<?php

namespace JonathanRayln\Core\Form;

use JonathanRayln\Core\Base\Model;

class InputField extends BaseField
{
    private const TYPE_TEXT = 'text';
    private const TYPE_PASSWORD = 'password';
    private const TYPE_NUMBER = 'number';
    private const TYPE_EMAIL = 'email';
    private const TYPE_HIDDEN = 'hidden';
    private string $type;


    public function __construct(Model $model, string $attribute, ?string $placeholder = null)
    {
        $this->placeholder = $placeholder;
        $this->type = self::TYPE_TEXT;

        parent::__construct($model, $attribute);
    }

    public function passwordField(): static
    {
        $this->type = self::TYPE_PASSWORD;

        return $this;
    }

    public function emailField(): static
    {
        $this->type = self::TYPE_EMAIL;

        return $this;
    }

    public function numberField(): static
    {
        $this->type = self::TYPE_NUMBER;

        return $this;
    }

    public function hiddenField(): static
    {
        $this->type = self::TYPE_HIDDEN;

        return $this;
    }

    /** @inheritDoc */
    protected function renderInput(): string
    {
        return sprintf('<input type="%s" id="%s" name="%s" value="%s" class="%s"%s%s>',
            $this->type,
            $this->attribute,
            $this->attribute,
            $this->model->{$this->attribute},
            $this->renderInputClasses(),
            $this->placeholder ? ' placeholder="' . $this->placeholder . '"' : '',
            $this->disabled ? ' disabled' : ''
        );
    }
}