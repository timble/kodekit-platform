<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Component Template Locator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\TemplateLoaderComponent
 */
class TemplateLocatorComponent extends TemplateLocatorAbstract
{
    /**
     * The type
     *
     * @var string
     */
    protected $_type = 'com';

    /**
     * Find a template path
     *
     * @param array  $info      The path information
     * @return bool|mixed
     */
    public function find(array $info)
    {
        $paths  = array();
        $loader = $this->getObject('manager')->getClassLoader();

        //Get the package
        $package = $info['package'];

        //Get the domain
        if(empty($info['domain'])) {
            $domain = $this->getObject('manager')->getLocator('com')->getPackage($info['package']);
        } else {
            $domain = $info['domain'];
        }

        //Base paths
        if($path = $loader->getLocator('component')->getNamespace('\\')) {
            $paths[] = $path.'/'.$package;
        }

        $namespace = ucfirst($domain).'\Component\\'.ucfirst($package);
        if($path = $loader->getLocator('component')->getNamespace($namespace)) {
            $paths[] = $path;
        }

        //File path
        $filepath = implode('/', $info['path']).'/templates/'.$info['file'].'.'.$info['format'].'.php';

        foreach($paths as $basepath)
        {
            if($result = $this->realPath($basepath.'/'.$filepath)) {
                return $result;
            }
        }

        return false;
    }
}