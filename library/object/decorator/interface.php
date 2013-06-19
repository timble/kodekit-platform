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
interface ObjectDecoratorInterface extends ObjectInterface, ObjectHandlable, ObjectMixable, ObjectDecoratable
{
    /**
     * Get the decorated object
     *
     * @return ObjectDecoratable The decorated object
     */
    public function getDelegate();

    /**
     * Set the decorated object
     *
     * @param   ObjectDecoratable $delegate The decorated object
     * @return  ObjectDecorator
     */
    public function setDelegate(ObjectDecoratable $delegate);

    /**
     * Decorate Notifier
     *
     * This function is called when an object is being decorated. It will get the object passed in.
     *
     * @param ObjectDecoratable $delegate The object being decorated
     * @return void
     */
    public function onDecorate(ObjectDecoratable $delegate);
}