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
 * Object Decorator Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
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