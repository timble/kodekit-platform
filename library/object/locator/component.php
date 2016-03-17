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
     * Returns a fully qualified class name for a given identifier.
     *
     * @param ObjectIdentifier $identifier An identifier object
     * @param bool  $fallback   Use the fallback sequence to locate the identifier
     * @return string|false  Return the class name on success, returns FALSE on failure if searching for a fallback
     */
    public function locate(ObjectIdentifier $identifier, $fallback = true)
    {
        $domain  = $identifier->domain ? ucfirst($identifier->domain) : null;
        $package = ucfirst($identifier->package);
        $file    = ucfirst($identifier->name);
        $path    = $identifier->path;

        $class   = StringInflector::camelize(implode('_', $identifier->path)).ucfirst($identifier->name);

        //Make an exception for 'view' and 'module' types
        $type = !empty($path) ? array_shift($path) : '';

        if(in_array($type, array('view','module')) && !in_array('behavior', $path)) {
            $path = ucfirst($type);
        } else {
            $path = ucfirst($type).StringInflector::camelize(implode('_', $path));
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
            'domain'     => $domain,
            'package'    => $package,
            'path'       => $path,
            'file'       => $file
        );

        return $this->find($info, $fallback);
    }

    /**
     * Get the list of class templates for an identifier
     *
     * @param string $identifier The package identifier
     * @return array The class templates for the identifier
     */
    public function getClassTemplates($identifier)
    {
        //Identifier
        $templates = array();

        //Fallback
        if($namespaces = $this->getIdentifierNamespaces($identifier))
        {
            foreach($namespaces as $namespace)
            {
                //Handle class prefix vs class namespace
                if(strpos($namespace, '\\')) {
                    $namespace .= '\\';
                }

                $templates[] = $namespace.'<Class>';
                $templates[] = $namespace.'<Path><File>';
            }
        }

        //Library
        $templates[] = __NAMESPACE__.'\<Path><File>';
        $templates[] = __NAMESPACE__.'\<Path>Default';

        return $templates;
    }
}