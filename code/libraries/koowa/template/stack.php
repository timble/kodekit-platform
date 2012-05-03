<?php
/**
 * @version     $Id: template.php 2026 2010-05-14 16:47:03Z johanjanssens $
 * @package     Koowa_Template
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

 /**
  * Template Stack Class
  *
  * Implements a simple stack collection (LIFO)
  *
  * The stack is implemented as a signleton. After instantiation the object can
  * be accessed using koowa:template.stack identifier.
  *
  * @author     Johan Janssens <johan@nooku.org>
  * @category   Koowa
  * @package    Koowa_Template
  */
class KTemplateStack extends KObject implements KServiceInstantiatable
{
    /**
     * The object container
     *
     * @var array
     */
    protected $_object_stack = null;

    /**
     * Constructor
     *
     * Prevent creating instances of this class by making the contructor private
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_object_stack = array();
    }

 	/**
     * Force creation of a singleton
     *
     * @param 	object 	An optional KConfig object with configuration options
     * @param 	object	A KServiceServiceInterface object
     * @return KTemplateStack
     */
    public static function getInstance(KConfigInterface $config, KServiceInterface $container)
    {
        // Check if an instance with this identifier already exists or not
        if (!$container->has($config->service_identifier))
        {
            //Create the singleton
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $container->set($config->service_identifier, $instance);
        }

        return $container->get($config->service_identifier);
    }

    /**
     * Pushes an element at the end of the registry
     *
     * @param mixed  The path
     * @return KTemplateStack
     */
    public function push(KTemplateAbstract $template)
    {
        $this->_object_stack[] = $template;
        return $this;
    }

    /**
     * Peeks at the element from the end of the registry
     *
     * @param mixed The value of the top element
     */
    public function top()
    {
        return end($this->_object_stack);
    }

    /**
     * Pops an element from the end of the registry
     *
     * @return  mixed The value of the popped element
     */
    public function pop()
    {
        return array_pop($this->_object_stack);
    }

	/**
     * Counts the number of elements
     *
     * @return integer	The number of elements
     */
    public function count()
    {
        return count($this->_object_stack);
    }

    /**
     * Check to see if the registry is empty
     *
     * @return boolean	Return TRUE if the registry is empty, otherwise FALSE
     */
    public function isEmpty()
    {
        return empty($this->_object_stack);
    }
}