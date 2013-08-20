<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Object Decorator
 *
 * The object decorator implements the same interface as Object and allows to decorate any Object. To decorate a none
 * Object object create a decorator that extends from ObjectDecoratorAbstract instead.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
 */
abstract class ObjectDecorator extends ObjectDecoratorAbstract implements ObjectInterface, ObjectMixable, ObjectDecoratable
{
    /**
     * Checks if the decorated object or one of it's mixin's inherits from a class.
     *
     * @param   string|object $class  The class to check
     * @return  boolean  Returns TRUE if the object inherits from the class
     */
    public function inherits($class)
    {
        $result = false;
        $object = $this->getDelegate();

        if ($object instanceof ObjectMixable) {
            $result = $object->inherits($class);
        } else {
            $result = $object instanceof $class;
        }

        return $result;
    }

    /**
     * Mixin an object
     *
     * When using mixin(), the calling object inherits the methods of the mixed in objects, in a LIFO order.
     *
     * @@param   mixed  $mixin  An object that implements ObjectMixinInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @param    array $config  An optional associative array of configuration options
     * @return  ObjectInterface
     */
    public function mixin($mixin, $config = array())
    {
        $this->getDelegate()->mixin($mixin, $config);
        return $this;
    }

    /**
     * Decorate the object
     *
     * When using decorate(), the object will be decorated by the decorator
     *
     * @@param   mixed  $decorator  An object that implements ObjectDecorator, ObjectIdentifier object
     *                              or valid identifier string
     * @param    array $config  An optional associative array of configuration options
     * @return   ObjectDecorator
     */
    public function decorate($decorator, $config = array())
    {
        $decorator = $this->getDelegate()->decorate($decorator, $config);

        //Notify the decorator and set the delegate
        $decorator->onDecorate($this);

        return $decorator;
    }

    /**
     * Set the decorated object
     *
     * @param   ObjectInterface $delegate The object to decorate
     * @return  ObjectDecorator
     */
    public function setDelegate($delegate)
    {
        if (!$delegate instanceof ObjectInterface) {
            throw new \InvalidArgumentException('Delegate needs to implement ObjectInterface');
        }

        return parent::setDelegate($delegate);
    }

    /**
     * Get an instance of a class based on a class identifier only creating it if it does not exist yet.
     *
     * @param	string|object	$identifier The class identifier or identifier object
     * @param	array  			$config     An optional associative array of configuration settings.
     * @return	Object Return object on success, throws exception on failure
     */
    public function getObject($identifier = null, array $config = array())
    {
        return $this->getDelegate()->getObject($identifier, $config);
    }

    /**
     * Get an object identifier.
     *
     * @param	string|object	$identifier A valid identifier string or object implementing ObjectInterface
     * @return	ObjectIdentifier
     */
    public function getIdentifier($identifier = null)
    {
        return $this->getDelegate()->getIdentifier($identifier);
    }

    /**
     * Get the object configuration
     *
     * @param   string|object    $identifier A valid identifier string or object implementing ObjectInterface
     * @return ObjectConfig
     */
    public function getConfig($identifier = null)
    {
        return $this->getDelegate()->getObject($identifier);
    }
}