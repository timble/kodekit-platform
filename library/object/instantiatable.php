<?php
/**
 * @package     Koowa_Object
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Library;

/**
 * Object Instantiatable Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Object
 */
interface ObjectInstantiatable
{
    /**
     * Instantiate the object
     *
     *  @param 	Config                  $config	  A Config object with configuration options
     * @param 	ObjectManagerInterface	$manager  A ObjectInterface object
     * @return  object
     */
    public static function getInstance(Config $config, ObjectManagerInterface $manager);
}