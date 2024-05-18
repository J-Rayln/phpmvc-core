<?php

namespace JonathanRayln\Core\Form;

use JonathanRayln\Core\Base\Model;

class SelectField extends BaseField
{
    private array $options;
    private string $valueKey;
    private string $labelKey;
    protected array $defaultInputClasses = ['form-select'];

    public function __construct(Model $model, string $attribute, array $options, string $valueKey, string $labelKey)
    {
        $this->options = $options;
        $this->valueKey = $valueKey;
        $this->labelKey = $labelKey;

        parent::__construct($model, $attribute);
    }

    /**
     * @inheritDoc
     */
    protected function renderInput(): string
    {
        return sprintf(
            '<select id="%s" name="%s" class="%s" aria-label="%s"%s>%s</select>',
            $this->attribute,
            $this->attribute,
            $this->renderInputClasses(),
            $this->model->getLabel($this->attribute),
            $this->disabled ? ' disabled' : '',
            $this->renderOptions()
        );
    }

    private function renderOptions(): string
    {
        $options = '';

        foreach ($this->options as $option) {

            if (is_object($option)) {

                $value = $option->{$this->valueKey};
                $label = $option->{$this->labelKey};

            } else {

                $value = $option[$this->valueKey];
                $label = $option[$this->labelKey];
            }

            $selected = $this->model->{$this->attribute} == $value ? ' selected' : '';

            $options .= sprintf('<option value="%s"%s>%s</option>', $value, $selected, $label);
        }

        return $options;
    }
}