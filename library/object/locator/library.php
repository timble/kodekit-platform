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
 * Library Object Locator
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
 */
class ObjectLocatorLibrary extends ObjectLocatorAbstract
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
     * @param   ObjectConfig $config An optional ObjectConfig object with configuration options.
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'fallbacks' => array(
                'Nooku\Library\<Package><Path><Name>',
                'Nooku\Library\<Package><Path>Default',
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
     * @param  ObjectIdentifier $identifier  	An identifier object
     * @return string	Returns the path
     */
    public function findPath(ObjectIdentifier $identifier)
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