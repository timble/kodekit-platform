<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Mixin
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Mixes a chain of command behaviour into a class
 *  
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Mixin
 */
interface KMixinInterface extends KObjectHandlable
{   
    /**
     * Get the methods that are available for mixin. 
     * 
     * @return array An array of methods
     */
    public function getMixableMethods();
    
    /**
     * Notification function called when the mixin is being mixed
     * 
     * @return void
     */
    public function onMixin();
}