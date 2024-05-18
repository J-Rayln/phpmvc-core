<?php

namespace JonathanRayln\Core\Form;

use JonathanRayln\Core\Base\Model;

abstract class BaseField
{
    protected Model $model;
    protected string $attribute;
    protected array $defaultInputClasses = ['form-control'];
    protected array $additionalInputClasses = [];
    protected array $inputAttributes = [];

    protected ?string $placeholder;
    protected bool $disabled = false;

    /**
     * @param Model  $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    /**
     * Renders the fully composed input field.
     *
     * @return string
     */
    abstract protected function renderInput(): string;

    /**
     * Renders the invalid feedback div.
     *
     * @return string
     */
    protected function renderInvalidFeedback(): string
    {
        return sprintf('<div class="invalid-feedback d-block">%s</div>',
            $this->model->getFirstError($this->attribute));
    }

    public function disabled(): static
    {
        $this->disabled = true;

        return $this;
    }

    public function addInputClasses(string|array $css): static
    {
        foreach ($this->buildClassArray($css) as $selector) {
            $this->additionalInputClasses[] = $selector;
        }

        $this->defaultInputClasses = array_merge($this->defaultInputClasses, $this->additionalInputClasses);

        return $this;
    }

    public function overrideInputClasses(string|array $css): static
    {
        $this->defaultInputClasses = [];

        foreach ($this->buildClassArray($css) as $selector) {
            $this->defaultInputClasses[] = $selector;
        }

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('
            <div class="mb-3">
                <label for="%s" class="form-label">%s</label>
               %s%s
            </div>
        ', $this->attribute,
            $this->model->getLabel($this->attribute),
            $this->renderInput(),
            $this->model->hasError($this->attribute) ? $this->renderInvalidFeedback() : ''
        );
    }

    protected function buildClassArray(string|array $css): array
    {
        if (is_string($css)) {
            // Replace any commas in the provided $css string
            $css = str_replace(',', ' ', $css);

            // Remove any duplicate white space.
            $css = preg_replace('!\s+!', ' ', $css);

            // Turn the remaining string into an array
            $css = explode(' ', $css);
        }

        return $css;
    }

    protected function renderInputClasses(): string
    {
        if ($this->model->hasError($this->attribute)) {
            $this->defaultInputClasses[] = 'is-invalid';
        }

        if ($this->disabled) {
            $this->defaultInputClasses[] = 'disabled';
        }

        return implode(' ', $this->defaultInputClasses);
    }
}