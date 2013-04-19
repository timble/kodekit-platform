<?php
/**
 * @package		Koowa_Object
 * @subpackage  Identifier
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Library;

/**
 * Object Identifier interface
 *
 * Wraps identifiers of the form type://package.[.path].name in an object, providing public accessors and methods for
 * derived formats.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Object
 * @subpackage  Identifier
 */
interface ObjectIdentifierInterface extends \Serializable
{
    /**
     * Formats the indentifier as a [application::]type.component.[.path].name string
     *
     * @return string
     */
    public function toString();
}