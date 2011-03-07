<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Event
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Class to handle events.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Event
 */
class KEventListener extends KObject implements KPatternObserver, KObjectIdentifiable
{
    /**
     * Get the object identifier
     * 
     * @return  KIdentifier 
     * @see     KObjectIdentifiable
     */
    public function getIdentifier()
    {
        return $this->_identifier;
    }
    
    /**
     * Method to trigger events
     *
     * @param  object   The event arguments
     * @return mixed Routine return value
     */
    public function update(KConfig $args)
    {       
        if (in_array($args->event, $this->getMethods())) {
            return $this->{$args->event}($args);
        } 
        
        return null;
    }
}