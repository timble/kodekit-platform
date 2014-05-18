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
 * Object Instantiable Interface
 *
 * The interface signals the ObjectManager to delegate object instantiation.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
 * @see     ObjectManager::getObject()
 */
interface ObjectInstantiable
{
    /**
     * Instantiate the object
     *
     * @param 	ObjectConfig            $config	  A ObjectConfig object with configuration options
     * @param 	ObjectManagerInterface	$manager  A ObjectManagerInterface object
     * @return  ObjectInterface
     */
    public static function getInstance(ObjectConfig $config, ObjectManagerInterface $manager);
}