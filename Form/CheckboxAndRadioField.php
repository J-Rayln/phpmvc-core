<?php

namespace JonathanRayln\Core\Form;

use JonathanRayln\Core\Base\Model;

class CheckboxAndRadioField
{
    private Model $model;
    private string $attribute;
    private array $options;
    private string $valueKey;
    private string $labelKey;
    private string $type;

    public function __construct(Model $model, string $attribute, array $options, string $valueKey, string $labelKey, string $type)
    {
        $this->model = $model;
        $this->attribute = $attribute;
        $this->options = $options;
        $this->valueKey = $valueKey;
        $this->labelKey = $labelKey;
        $this->type = $type;
    }

    protected function renderInput(): string
    {
        $options = '';

        $num = 0;

        foreach ($this->options as $option) {
            $num++;

            if (is_object($option)) {

                $value = $option->{$this->valueKey};
                $label = $option->{$this->labelKey};

            } else {

                $value = $option[$this->valueKey];
                $label = $option[$this->labelKey];
            }

            if (is_array($this->model->{$this->attribute})) {
                // TODO: checkboxes don't render "selected" when checked after form submission or when loading from DB.
                $selected = '';

            } else {
                $selected = $this->model->{$this->attribute} == $value ? ' checked' : '';
            }

            $name = $this->type == 'checkbox' ? $this->attribute . '[]' : $this->attribute;

            $options .= sprintf(
                '<div class="form-check">
                        <input type="%s" name="%s" id="%s" value="%s" class="form-check-input"%s>
                        <label for="%s" class="form-check-label">%s</label>
                        </div>',
                $this->type,
                $name,
                $this->attribute . '_' . $num,
                $value,
                $selected,
                $this->attribute . '_' . $num,
                $label
            );
        }

        return $options;
    }

    public function __toString(): string
    {
        return sprintf('
            <div class="mb-3">
                <div class="form-label">%s</div>
               %s%s
            </div>
        ', $this->model->getLabel($this->attribute),
            $this->renderInput(),
            $this->model->hasError($this->attribute) ? $this->renderInvalidFeedback() : ''
        );
    }

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
}