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
 * Component Template Locator
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\TemplateLoaderComponent
 */
class TemplateLocatorComponent extends TemplateLocatorAbstract
{
    /**
     * Locate the template based on a virtual path
     *
     * @param  string $path  Stream path or resource
     * @return string   The physical stream path for the template
     */
    public function locate($path)
    {
        $paths = array();

        //Qualify partial templates.
        if(strpos($path, ':') === false)
        {
            if(!$base = $this->getTemplate()->getPath()) {
                throw new \RuntimeException('Cannot qualify partial template path');
            }

            $identifier = $this->getIdentifier($base)->toArray();

            $format    = pathinfo($path, PATHINFO_EXTENSION);
            $template  = pathinfo($path, PATHINFO_FILENAME);

            $parts     = $identifier['path'];
            array_pop($parts);
        }
        else
        {
            // Need to clone here since we use array_pop and it modifies the cached identifier
            $identifier = $this->getIdentifier($path)->toArray();

            $format    = $identifier['name'];
            $template  = array_pop($identifier['path']);

            $parts     = $identifier['path'];
        }

        $package   = $identifier['package'];
        $domain    = $identifier['domain'] ? $identifier['domain'] : 'nooku';

        //Find the template paths
        $namespaces = $this->getObject('manager')->getClassLoader()->getLocator('component')->getNamespaces();

        //Userland template path
        $paths[] = $namespaces[''].'/'.$package;

        //Namespace template path
        $namespace = ucfirst($domain).'\Component\\'.ucfirst($package);
        if(!isset($namespaces[$namespace]))
        {
            $namespace = ucfirst($domain).'\Component';
            if(isset($namespaces[$namespace])) {
                $paths[] = $namespaces[$namespace].'/'.$package;
            }
        }
        else $paths[] = $namespaces[$namespace];

        //Find the template file
        $filepath = implode('/', $parts).'/templates/'.$template.'.'.$format.'.php';

        foreach($paths as $basepath)
        {
            if($result = $this->realPath($basepath.'/'.$filepath)) {
                return $result;
            }
        }

        return false;
    }
}