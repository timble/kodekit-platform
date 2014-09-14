<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Command Context
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Command
 */
class Command extends ObjectConfig implements CommandInterface
{
    /**
     * The event name
     *
     * @var array
     */
    protected $_name;

    /**
     * Subject of the command
     *
     * @var mixed
     */
    protected $_subject;

    /**
     * Constructor.
     *
     * @param  string              $name       The command name
     * @param  array|\Traversable  $attributes An associative array or a Traversable object instance
     * @param  mixed               $subject    The command subject
     */
    public function __construct($name = '', $attributes = array(), $subject = null)
    {
        parent::__construct($attributes);

        $this->setName($name);
        $this->setSubject($subject);
    }

    /**
     * Get the event name
     *
     * @return string	The event name
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Set the event name
     *
     * @param string $name  The event name
     * @return Command
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * Get the command subject
     *
     * @return mixed The command subject
     */
    public function getSubject()
    {
        return $this->_subject;
    }

    /**
     * Set the command subject
     *
     * @param mixed $subject The command subject
     * @return Command
     */
    public function setSubject($subject)
    {
        $this->_subject = $subject;
        return $this;
    }

    /**
     * Set attributes
     *
     * Overwrites existing attributes
     *
     * @param  array|\Traversable $attributes
     * @throws \InvalidArgumentException If the attributes are not an array or are not traversable.
     * @return Command
     */
    public function setAttributes($attributes)
    {
        if (!is_array($attributes) && !$attributes instanceof \Traversable)
        {
            throw new \InvalidArgumentException(sprintf(
                'Command attributes must be an array or an object implementing the Traversable interface; received "%s"', gettype($attributes)
            ));
        }

        //Set the arguments.
        foreach ($attributes as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * Get all arguments
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->toArray();
    }

    /**
     * Get an attribute
     *
     * If the attribute does not exist, the $default value will be returned.
     *
     * @param  string $name The attribute name
     * @param  mixed $default
     * @return mixed
     */
    public function getAttribute($name, $default = null)
    {
        return $this->get($name, $default);
    }

    /**
     * Set an attribute
     *
     * @param  string $name The attribute
     * @param  mixed $value
     * @return Command
     */
    public function setAttribute($name, $value)
    {
        $this->set($name, $value);
        return $this;
    }

    /**
     * Get a new instance
     *
     * @return ObjectConfig
     */
    final public function getInstance()
    {
        $instance = new ObjectConfig(array(), $this->_readonly);
        return $instance;
    }

    /**
     * Get an command property or attribute
     *
     * If an event property exists the property will be returned, otherwise the attribute will be returned. If no
     * property or attribute can be found the method will return NULL.
     *
     * @param  string $name    The property name
     * @param  mixed  $default The default value
     * @return mixed|null  The property value
     */
    final public function get($name, $default = null)
    {
        $method = 'get'.ucfirst($name);
        if(!method_exists($this, $method) ) {
            $value = parent::get($name);
        } else {
            $value = $this->$method();
        }

        return $value;
    }

    /**
     * Set a command property or attribute
     *
     * If an event property exists the property will be set, otherwise an attribute will be added.
     *
     * @param  string $name
     * @param  mixed  $value
     * @return Command
     */
    final public function set($name, $value)
    {
        $method = 'set'.ucfirst($name);
        if(!method_exists($this, $method) ) {
            parent::set($name, $value);
        } else {
            $this->$method($value);
        }

        return $this;
    }
}
