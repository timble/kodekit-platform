<?php
/**
* @package      Nooku_Components
* @subpackage   Default
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link         http://www.nooku.org
*/

jimport('joomla.registry.format');
jimport('joomla.registry.format.ini');

use Nooku\Framework;

/**
 * INI filter
 *
 * The format is non-standard, used by JRegistry as seen in JParameter
 *
 * @author      Stian Didriksen <stian@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 * @uses        JRegistryFormatINI
 */
class ComBaseFilterIni extends Framework\FilterAbstract
{
    /**
     * Constructor
     *
     * @param   object  An optional Framework\Config object with configuration options
     */
    public function __construct(Framework\Config $config)
    {
        parent::__construct($config);

        //Don't walk the incoming data array or object
        $this->_walk = false;
    }

    /**
     * Validate a value
     *
     * @param    scalar Value to be validated
     * @return   bool   True when the variable is valid
     */
    protected function _validate($value)
    {
        $handler = JRegistryFormat::getInstance('INI');
        return is_string($value) && !is_null($handler->stringToObject($value));
    }

    /**
     * Sanitize a value
     *
     * @param   string  Value to be sanitized
     * @return  ComBaseConfigIni
     */
    protected function _sanitize($value)
    {
        if(!$value instanceof ComBaseConfigIni)
        {
            if(is_string($value))
            {
                $handler = JRegistryFormat::getInstance('INI');
                $value   = (array) $handler->stringToObject($value);
            }

            $value = new ComBaseConfigIni($value);
        }

        return $value;
    }
}