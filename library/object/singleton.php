<?php
/**
 * @package     Koowa_Object
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Object Singleton Interface
 *
 * The interface signals the ObjectManager to register the object into the object registry upon instantiation.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Object
 * @see         ObjectManager::get()
 */
interface ObjectSingleton extends ObjectInterface
{

}