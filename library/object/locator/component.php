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
 * Component Object Locator
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
 */
class ObjectLocatorComponent extends ObjectLocatorAbstract
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
     * @param   ObjectConfig $config An optional ObjectConfig object with configuration options.
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'fallbacks' => array(
                '<Package><Class>',
                'Nooku\Component\<Package>\<Class>',
                'Nooku\Component\<Package>\<Path><Name>',
                'Nooku\Library\<Path><Name>',
                'Nooku\Library\<Path>Default',
            )
        ));
    }

    /**
     * Returns a fully qualified class name for a given identifier.
     *
     * @param ObjectIdentifier $identifier An identifier object
     * @return string|false  Return the class name on success, returns FALSE on failure
     */
    public function locate(ObjectIdentifier $identifier)
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
     * @param  ObjectIdentifier $identifier  	An identifier object
     * @return string	Returns the path
     */
    public function findPath(ObjectIdentifier $identifier)
    {
        $path  = '';
        $parts = $identifier->path;

        $component = strtolower($identifier->package);

        if(!empty($identifier->name))
        {
            if(count($parts)) {
                $path = implode('/', $parts).'/'.strtolower($identifier->name);
            } else {
                $path  = strtolower($identifier->name);
            }
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