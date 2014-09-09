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
 * Component Template Locator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Template\Locator\Component
 */
class TemplateLocatorComponent extends TemplateLocatorIdentifier
{
    /**
     * The locator name
     *
     * @var string
     */
    protected static $_name = 'com';

    /**
     * Find a template path
     *
     * @param array  $info      The path information
     * @return string|false The real template path or FALSE if the template could not be found
     */
    public function find(array $info)
    {
        $result = false;
        $paths  = array();
        $loader = $this->getObject('manager')->getClassLoader();

        //Get the package
        $package = $info['package'];

        //Base paths
        if($path = $loader->getLocator('component')->getNamespace('\\')) {
            $paths[] = $path.'/'.$package;
        }

        $namespace = $this->getObject('object.bootstrapper')->getComponentNamespace($package);
        if($path = $loader->getLocator('component')->getNamespace($namespace)) {
            $paths[] = $path;
        }

        //If no type exists create a glob pattern
        if(!empty($info['type'])){
            $filepath =  implode('/', $info['path']).'/templates/'.$info['file'].'.'.$info['format'].'.'.$info['type'];
        } else {
            $filepath =  implode('/', $info['path']).'/templates/'.$info['file'].'.'.$info['format'].'.*';
        }

        foreach($paths as $basepath)
        {
            $pattern = $basepath .'/view/'. $filepath;
            $results = glob($pattern);

            //Try to find the file
            if ($results)
            {
                foreach($results as $file)
                {
                    if($result = $this->realPath($file)) {
                        break;
                    }
                }
            }
        }

        return $result;
    }
}