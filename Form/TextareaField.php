<?php

namespace JonathanRayln\Core\Form;

use JonathanRayln\Core\Base\Model;

class TextareaField extends BaseField
{
    private int $rows = 10;
    private int $columns = 30;

    public function __construct(Model $model, string $attribute, $placeholder)
    {
        $this->placeholder = $placeholder;

        parent::__construct($model, $attribute);
    }

    public function rows(int $rows): static
    {
        $this->rows = $rows;

        return $this;
    }

    public function columns(int $columns): static
    {
        $this->columns = $columns;

        return $this;
    }

    /** @inheritDoc */
    protected function renderInput(): string
    {
        return sprintf('<textarea id="%s" name="%s" cols="%s" rows="%s" class="%s"%s%s>%s</textarea>',
            $this->attribute,
            $this->attribute,
            $this->columns,
            $this->rows,
            $this->renderInputClasses(),
            $this->placeholder ? ' placeholder="' . $this->placeholder . '"' : '',
            $this->disabled ? ' disabled' : '',
            $this->model->{$this->attribute}
        );
    }
}
