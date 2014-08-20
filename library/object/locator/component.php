<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Component Object Locator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Object\Locator\Component
 */
class ObjectLocatorComponent extends ObjectLocatorAbstract
{
    /**
     * The locator name
     *
     * @var string
     */
    protected static $_name = 'com';

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
            'sequence' => array(
                '<Package><Class>',
                '<Domain>\Component\<Package>\<Class>',
                '<Domain>\Component\<Package>\<Path><File>',
                'Nooku\Library\<Path><File>',
                'Nooku\Library\<Path>Default',
            )
        ));
    }

    /**
     * Returns a fully qualified class name for a given identifier.
     *
     * @param ObjectIdentifier $identifier An identifier object
     * @param bool  $fallback   Use the fallback sequence to locate the identifier
     * @return string|false  Return the class name on success, returns FALSE on failure if searching for a fallback
     */
    public function locate(ObjectIdentifier $identifier, $fallback = true)
    {
        if(empty($identifier->domain)) {
            $domain  = ucfirst($this->getObject('object.bootstrapper')->getComponentDomain($identifier->package));
        } else {
            $domain = ucfirst($identifier->domain);
        }

        $package = ucfirst($identifier->package);
        $file    = ucfirst($identifier->name);
        $path    = $identifier->path;

        $class   = StringInflector::camelize(implode('_', $identifier->path)).ucfirst($identifier->name);

        //Make an exception for 'view' and 'module' types
        $type = !empty($path) ? array_shift($path) : '';

        if(!in_array($type, array('view','module'))) {
            $path = ucfirst($type).StringInflector::camelize(implode('_', $path));
        } else {
            $path = ucfirst($type);
        }

        //Allow locating default classes if $path is empty.
        if(empty($path))
        {
            $path = $file;
            $file = '';
        }

        $info = array(
            'identifier' => $identifier,
            'class'      => $class,
            'package'    => $package,
            'domain'     => $domain,
            'path'       => $path,
            'file'       => $file
        );

        return $this->find($info, $fallback);
    }
}