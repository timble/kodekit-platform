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
                '<Package><Path>Default',
                'Nooku\Component\<Package>\<Class>',
                'Nooku\Component\<Package>\<Path><Name>',
                'Nooku\Component\<Package>\<Path>Default',
                'Base<Path><Name>',
                'Base<Path>Default',
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
        $path  = Inflector::camelize(implode('_', $identifier->path));
        $class = ucfirst($identifier->package).$path.ucfirst($identifier->name);

        //Manually load the class to set the basepath
        if (!$this->getService('loader')->loadClass($class, $identifier->basepath.'/component'))
        {
            $classname = $path.ucfirst($identifier->name);
            $classpath = $identifier->path;
            $classtype = !empty($classpath) ? array_shift($classpath) : '';

            //Create the fallback path and make an exception for views and modules
            if(!in_array($classtype, array('view','module'))) {
                $path = ucfirst($classtype).Inflector::camelize(implode('_', $classpath));
            } else {
                $path = ucfirst($classtype);
            }

            $name    = ucfirst($identifier->name);
            $package = ucfirst($identifier->package);

            $class = false;
            foreach($this->_fallbacks as $fallback)
            {
                $class = str_replace(
                    array('<Package>', '<Path>', '<Name>', '<Class>'),
                    array($package   , $path   , $name   , $classname),
                    $fallback
                );

                if(!class_exists($class)) {
                    $class = false;
                } else {
                    break;
                }
            }

        }

        return $class;
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
                        $parts[$key] = Inflector::pluralize($value);
                    }
                }
                else $parts[0] = Inflector::pluralize($parts[0]);

                $path = implode('/', $parts).'/'.strtolower($identifier->name);
            }
            else $path  = strtolower($identifier->name);
        }

        $path = 'component/'.$component.'/'.$path.'.php';

        if(file_exists($identifier->basepath.'/'.$path)) {
            $path = $identifier->basepath.'/'.$path;
        } else {
            $path = JPATH_ROOT.'/'.$path;
        }

        return $path;
    }
}