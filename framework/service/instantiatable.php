<?php
/**
 * @package     Koowa_Service
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Framework;

/**
 * Service Instantiatable Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Service
 */
interface ServiceInstantiatable
{
    /**
     * Instantiate the object
     *
     *  @param 	Config                  $config	  A Config object with configuration options
     * @param 	ServiceManagerInterface	$manager  A ServiceInterface object
     * @return  object
     */
    public static function getInstance(Config $config, ServiceManagerInterface $manager);
}