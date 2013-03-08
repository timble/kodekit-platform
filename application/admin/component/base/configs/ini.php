<?php
/**
 * @package     Nooku_Server
 * @subpackage  Config
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

jimport('joomla.registry.format');
jimport('joomla.registry.format.ini');

use Nooku\Framework;

/**
 * INI Config Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Config
 */
class ComBaseConfigIni extends Framework\Config
{
    /**
     * Returns a string in INI format
     *
     * @return string
     */
    public function toString()
    {
        $data = (object) $this->toArray();

        return JRegistryFormat::getInstance('INI')->objectToString($data, null);
    }
}