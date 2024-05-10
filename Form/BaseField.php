<?php

namespace JonathanRayln\Core\Form;

use JonathanRayln\Core\Base\Model;

abstract class BaseField
{
    public Model $model;
    public string $attribute;

    /**
     * @param Model  $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    abstract public function renderInput(): string;

    protected function renderInvalidFeedback()
    {
        return sprintf('<div class="invalid-feedback d-block">%s</div>',
            $this->model->getFirstError($this->attribute));
    }

    public function __toString(): string
    {
        return sprintf('
            <div class="mb-3">
                <label for="%s" class="form-label">%s</label>
               %s
               %s
            </div>
        ', $this->attribute,
            $this->model->getLabel($this->attribute),
            $this->renderInput(),
            $this->model->hasError($this->attribute) ? $this->renderInvalidFeedback() : ''
        );
    }
}