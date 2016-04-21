<?php namespace inkvizytor\FluentForm\Validation;

/**
 * Class Nag
 *
 * @package inkvizytor\FluentForm
 */
class Nag extends Base
{
    /** @var \DragonFly\Nag\Converters\Contract */
    private $converter = null;

    /** @var array */
    public $rules = [];

    /** @var array */
    protected $messages = [];

    /** @var array */
    protected $useLabel = true;


    /**
     * Class constructor
     */
    public function __construct()
    {
        $className = 'DragonFly\\Nag\\Converters\\'.config('nag.driver', 'FormValidation');

        $this->converter = app()->make($className);
    }

    /**
     * @param array $rules
     * @param array $messages
     */
    public function setRules($rules, $messages = [], $useLabel = true)
    {
        $this->rules = $rules;
        $this->messages = $messages;
    }

    /**
     * @param string $name
     * @param string $label
     * @return array
     */
    public function getOptions($name, $label)
    {
        $options = [];

        if ($name == '__FORM')
        {
            return array_map(function($value) { return (string)$value; }, $this->converter->formOptions);
        }

        if ($this->ruleExist($name))
        {
            if (!$this->useLabel)
                $label = null;

            $options = $this->converter->convertRules($name, $this->getRules($name), $this->getMessages($name), $label);
        }

        return $options;
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function ruleExist($name)
    {
        return isset($this->rules[$name]);
    }

    /**
     * @param string $name
     * @return array
     */
    protected function getRules($name)
    {
        $rules = array_get($this->rules, $name, []);

        return is_array($rules) ? $rules : explode('|', $rules);
    }

    /**
     * @param string $name
     * @return array
     */
    protected function getMessages($name, $label = '')
    {
        $messages = [];
        foreach($this->messages as $key => $message)
        {
            if (stripos($key, $name.'.') === 0)
            {
                if (!empty($label))
                    $messages[$key] = str_replace(":attribute", $label, $message);
                else
                    $messages[$key] = $message;
            }
        }

        return $messages;
    }
}