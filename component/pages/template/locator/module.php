<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Module Template Locator
 *
 * @author  Johan Janssens <https://github.com/johanjanssens>
 * @package Kodekit\Component\Pages
 */
class TemplateLocatorModule extends Library\TemplateLocatorIdentifier
{
    /**
     * The locator name
     *
     * @var string
     */
    protected static $_name = 'mod';

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

        //Base paths
        $paths = $this->getObject('object.bootstrapper')->getComponentPath($info['package'], $info['domain']);

        //If no type exists create a glob pattern
        if(!empty($info['type'])){
            $filepath =  implode('/', $info['path']).'/templates/'.$info['file'].'.'.$info['format'].'.'.$info['type'];
        } else {
            $filepath =  implode('/', $info['path']).'/templates/'.$info['file'].'.'.$info['format'].'.*';
        }

        foreach($paths as $basepath)
        {
            $pattern = $basepath .'/module/'. $filepath;
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