<?php
/**
 * @version     $Id$
 * @category    Koowa
 * @package     Koowa_Factory
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Object Instantiatable Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Factory
 */
interface KObjectInstantiatable
{
    /**
     * Get the object identifier
     * 
     * @param 	array 	An optional array with configuration options
     * @param 	object	A KFactoryInterface object
     * @return  KIdentifier 
     */
    public static function getInstance($config = array(), KFactoryInterface $factory = null);
}