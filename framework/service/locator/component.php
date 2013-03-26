<?php
/**
 * @package		Koowa_Service
 * @subpackage 	Locator
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Framework;

/**
 * Locator Adapter for a component
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Service
 * @subpackage 	Locator
 */
class ServiceLocatorComponent extends ServiceLocatorAbstract
{
    /**
     * The type
     *
     * @var string
     */
    protected $_type = 'com';

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
                '<Package><Class>',
                'Nooku\Component\<Package>\<Class>',
                'Nooku\Component\<Package>\<Path><Name>',
                'Application<Path>Default',
                'Nooku\Framework\<Path><Name>',
                'Nooku\Framework\<Path>Default',
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
        $name    = ucfirst($identifier->name);

        //Make an exception for 'view' and 'module' types
        $path  = $identifier->path;
        $type  = !empty($path) ? array_shift($path) : '';

        if(!in_array($type, array('view','module'))) {
            $path = ucfirst($type).StringInflector::camelize(implode('_', $path));
        } else {
            $path = ucfirst($type);
        }

        //Allow locating default classes if $path is empty.
        if(empty($path))
        {
            $path = $name;
            $name = '';
        }

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
        $path  = '';
        $parts = $identifier->path;

        $component = strtolower($identifier->package);

        if(!empty($identifier->name))
        {
            if(count($parts))
            {
                if(!in_array($parts[0], array('view', 'module')))
                {
                    foreach($parts as $key => $value) {
                        $parts[$key] = StringInflector::pluralize($value);
                    }
                }
                else $parts[0] = StringInflector::pluralize($parts[0]);

                $path = implode('/', $parts).'/'.strtolower($identifier->name);
            }
            else $path  = strtolower($identifier->name);
        }

        $path = 'component/'.$component.'/'.$path.'.php';

        if(file_exists(JPATH_APPLICATION.'/'.$path)) {
            $path = JPATH_APPLICATION.'/'.$path;
        } else {
            $path = JPATH_ROOT.'/'.$path;
        }

        return $path;
    }
}