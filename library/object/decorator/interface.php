<?php
/**
 * @package     Koowa_Object
 * @subpackage  Decorator
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Object Decorator Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Object
 * @subpackage  Decorator
 */
interface ObjectDecoratorInterface extends ObjectInterface
{
    /**
     * Get the decorated object
     *
     * @return Object The decorated object
     */
    public function getDelegate();

    /**
     * Set the decorated object
     *
     * @param   Object $delegate The decorated object
     * @return  ObjectDecorator
     */
    public function setDelegate(Object $delegate);

    /**
     * Decorate Notifier
     *
     * This function is called when an object is being decorated. It will get the object passed in.
     *
     * @param Object $object The object being decorated
     * @return ObjectDecorator
     */
    public function onDecorate(Object $object);
}