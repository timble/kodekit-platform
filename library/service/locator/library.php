<?php
/**
 * @package		Koowa_Service
 * @subpackage 	Locator
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Library;

/**
 * Service Locator for the Koowa framework
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Service
 * @subpackage 	Locator
 */
class ServiceLocatorLibrary extends ServiceLocatorAbstract
{
    /**
     * The type
     *
     * @var string
     */
    protected $_type = 'lib';

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   Config $config An optional Config object with configuration options.
     * @return  void
     */
    protected function _initialize(Config $config)
    {
        $config->append(array(
            'fallbacks' => array(
                'Nooku\Library\<Package><Path><Name>',
                'Nooku\Library\<Package><Path>Default',
            )
        ));
    }

    /**
     * Find the identifier class
     *
     * @param ServiceIdentifier$identifier An identifier object
     * @return string|false  Return the class name on success, returns FALSE on failure
     */
    public function findClass(ServiceIdentifier $identifier)
    {
        $class   = StringInflector::camelize(implode('_', $identifier->path)).ucfirst($identifier->name);

        $package = ucfirst($identifier->package);
        $path    = StringInflector::camelize(implode('_', $identifier->path));
        $name    = ucfirst($identifier->name);

        $result = false;
        foreach($this->_fallbacks as $fallback)
        {
            $result = str_replace(
                array('<Package>', '<Path>', '<Name>', '<Class>'),
                array($package   , $path   , $name   , $class),
                $fallback
            );

            if(!class_exists($result)) {
                $result = false;
            } else {
                break;
            }
        }

        return $result;
    }

    /**
     * Find the identifier path
     *
     * @param  ServiceIdentifier $identifier  	An identifier object
     * @return string	Returns the path
     */
    public function findPath(ServiceIdentifier $identifier)
    {
        $path = '';

        if(count($identifier->path)) {
            $path .= implode('/',$identifier->path);
        }

        if(!empty($identifier->name)) {
            $path .= '/'.$identifier->name;
        }

        $path = JPATH_ROOT.'/library/'.$path.'.php';
        return $path;
    }
}