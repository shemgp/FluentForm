<?php namespace inkvizytor\FluentForm\Validation;

/**
 * Class Base
 *
 * @package inkvizytor\FluentForm
 */
abstract class Base
{
    /**
     * @param array $rules
     * @param array $messages
     * @param boolean $useLabel
     */
    public abstract function setRules($rules, $messages = [], $useLabel = true);

    /**
     * @param string $name
     * @param string $label
     * @return array
     */
    public abstract function getOptions($name, $label);
}