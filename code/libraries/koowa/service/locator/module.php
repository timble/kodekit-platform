<?php
/**
 * @version 	$Id$
 * @package		Koowa_Service
 * @subpackage 	Locator
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Service Locator for a plugin
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Service
 * @subpackage 	Locator
 */
class KServiceLocatorModule extends KServiceLocatorAbstract
{
    /**
     * The type
     *
     * @var string
     */
    protected $_type = 'mod';

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options.
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'prefixes' => array('ModDefault', 'ComDefault', 'K'),
        ));
    }

    /**
     * Get the classname
     *
     * This locator will try to create an generic or default classname on the identifier information
     * if the actual class cannot be found using the configurable fallback sequence.
     *
     * Fallback sequence : -> Named Module Specific
     *                     -> Named Module Default
     *                     -> Default Module Specific
     *                     -> Default Module Default
     *                     -> Framework Specific
     *                     -> Framework Default
     *
     * @param mixed  		 An identifier object - mod:[//application/]module.[.path].name
     * @return string|false  Return object on success, returns FALSE on failure
     */
    public function findClass(KServiceIdentifier $identifier)
    {
        $classes   = array();
        $path      = KInflector::camelize(implode('_', $identifier->path));
        $classname = 'Mod'.ucfirst($identifier->package).$path.ucfirst($identifier->name);

        //Don't allow the auto-loader to load module classes if they don't exists yet
        if (!$this->getService('koowa:loader')->loadClass($classname, $identifier->basepath))
        {
            $classpath = $identifier->path;
            $classtype = !empty($classpath) ? array_shift($classpath) : 'view';

            //Create the fallback path and make an exception for views
            $com_path = ($classtype != 'view') ? ucfirst($classtype).KInflector::camelize(implode('_', $classpath)) : ucfirst($classtype);
            $mod_path = ($classtype != 'view') ? ucfirst($classtype).KInflector::camelize(implode('_', $classpath)) : '';

            /*
             * Fallback sequence : -> Named Module Specific
             *                     -> Named Module Default
             *                     -> Default Module Specific
             *                     -> Default Module Default
             *                     -> Default Component Specific
             *                     -> Default Component Default
             *                     -> Framework Specific
             *                     -> Framework Default
             */

            //Add the classname to prevent re-look up
            $classes[] = $classname;

            //Add the package to look up defaults
            array_unshift($this->_prefixes, 'Mod'.ucfirst($identifier->package));

            $classname = false;
            foreach($this->_prefixes as $prefix)
            {
                $path = substr($prefix, 0, 3) == 'Com' ? $com_path : $mod_path;

                foreach(array($identifier->name, 'default') as $name)
                {
                    $classname = $prefix.$path.ucfirst($name);

                    if(!in_array($classname, $classes))
                    {
                        if(class_exists($classname))
                        {
                            $classes[] = $classname;
                            break(2);
                        }

                        $classes[] = $classname;
                    }
                }
            }
        }
        else $classes[] = $classname;

        return $classname;
    }

    /**
     * Get the path
     *
     * @param  object  	An identifier object - mod:[//application/]module.[.path].name
     * @return string	Returns the path
     */
    public function findPath(KServiceIdentifier $identifier)
    {
        $path  = '';
        $parts = $identifier->path;
        $name  = $identifier->package;

        if(!empty($identifier->name))
        {
            if(count($parts))
            {
                if($parts[0] != 'view')
                {
                    foreach($parts as $key => $value) {
                        $parts[$key] = KInflector::pluralize($value);
                    }
                }
                else $parts[0] = KInflector::pluralize($parts[0]);

                $path = implode('/', $parts).'/'.strtolower($identifier->name);
            }
            else $path  = strtolower($identifier->name);
        }

        $path = $identifier->basepath.'/modules/mod_'.$name.'/'.$path.'.php';
        return $path;
    }
}