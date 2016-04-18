<?php namespace inkvizytor\FluentForm\Base;

use inkvizytor\FluentForm\Traits\AttrContract;
use inkvizytor\FluentForm\Traits\HandlerContract;

/**
 * Class Control
 *
 * @package inkvizytor\FluentForm
 */
abstract class Control
{
    use HandlerContract, AttrContract;
    
    /** @var array */
    protected $guarded = [];

    /**
     * @param \inkvizytor\FluentForm\Base\Handler $handler
     */
    public function __construct(Handler $handler)
    {
        $this->setHandler($handler);

        if (method_exists($this, 'backupRenderer'))
        {
            $this->backupRenderer();
        }
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        $options = array_except(get_object_vars($this), $this->getGuarded());

        if (method_exists($this, 'appendAttributes'))
        {
            $this->appendAttributes($options);
        }
        
        if (method_exists($this, 'appendData'))
        {
            $this->appendData($options);
        }

        if (method_exists($this, 'appendCss'))
        {
            $this->appendCss($options);
        }

        return array_filter($options, function($item)
        {
            return $item !== NULL && $item !== FALSE && !is_array($item) && !is_object($item);
        });
    }

    /**
     * @return array
     */
    protected function getGuarded()
    {
        return array_merge(['handler', 'attr', 'css', 'data', 'guarded', 'inputGroup', 'renderer'], $this->guarded);
    }

    /**
     * @param string $name
     * @return string
     */
    protected function key($name)
    {
        return str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name);
    }

    /**
     * @return string
     */
    public abstract function render();

    /**
     * @return string
     */
    public function display()
    {
        return $this->renderer()
            ->bindControl($this)
            ->display();
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->display();
    }
} 
