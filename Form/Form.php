<?php

namespace JonathanRayln\Core\Form;

use JonathanRayln\Core\Base\Model;

class Form
{
    public function __construct(protected Model $model) {}

    public static function begin(Model $model, string $action, string $method): Form
    {
        echo sprintf('<form action="%s" method="%s">',
            $action, $method);

        return new Form($model);
    }

    public static function end(): void
    {
        echo '</form>';
    }

    public function input(string $attribute, ?string $placeholder = null): InputField
    {
        return new InputField($this->model, $attribute, $placeholder);
    }

    public function textarea(string $attribute, ?string $placeholder = null): TextareaField
    {
        return new TextareaField($this->model, $attribute, $placeholder);
    }

    /**
     * Outputs a form select field.
     *
     * @param string $attribute Property/Column name
     * @param array  $options   Array of associative arrays or objects that
     *                          hold the &lt;option&gt; values and text.
     * @param string $valueKey  The sub-array key to be used as the `value=`
     *                          attribute of the &lt;option&gt; tag.
     * @param string $labelKey  The sub-array key to be used as the display text
     *                          between the &lt;option&gt;&lt;/option&gt; tags.
     * @return SelectField
     */
    public function select(string $attribute, array $options, string $valueKey, string $labelKey): SelectField
    {
        return new SelectField($this->model, $attribute, $options, $valueKey, $labelKey);
    }

    public function radio(string $attribute, array $options, string $valueKey, string $labelKey): CheckboxAndRadioField
    {
        return new CheckboxAndRadioField(
            $this->model, $attribute, $options, $valueKey, $labelKey, 'radio');
    }

    public function checkbox(string $attribute, array $options, string $valueKey, string $labelKey): CheckboxAndRadioField
    {
        return new CheckboxAndRadioField(
            $this->model, $attribute, $options, $valueKey, $labelKey, 'checkbox');
    }
}