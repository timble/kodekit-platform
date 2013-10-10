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
        $result = false;
        $info   = pathinfo( $path );

        //Handle short hand identifiers
        if(strpos($info['filename'], '.') === false)
        {
            $path = pathinfo($this->getTemplate()->getPath());
            $identifier = $this->getIdentifier($path['filename']);
            $filename   = $info['filename'];
        }
        else
        {
            $identifier = $this->getIdentifier($info['filename']);
            $filename   = $identifier->name;
        }

        $extension = $info['extension'].'.php';
        $component = $identifier->package;
        $filepath  = $component.'/'.implode('/', $identifier->path).'/templates';

        //Find the file
        $paths    = $this->getObject('manager')->getClassLoader()->getLocator('com')->getNamespaces();
        $iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($paths));

        foreach($iterator as $basepath)
        {
            $file = $basepath.'/'.$filepath.'/'.$filename.'.'.$extension;
            if($result = $this->realPath($file)) {
                break;
            }
        }

        return $result;
    }

    /**
     * Get a path from an file
     *
     * Function will check if the path is an alias and return the real file path
     *
     * @param  string $file The file path
     * @return string The real file path
     */
    public function realPath($file)
    {
        $result = false;
        $path   = dirname($file);

        // Is the path based on a stream?
        if (strpos($path, '://') === false)
        {
            // Not a stream, so do a realpath() to avoid directory traversal attempts on the local file system.
            $path = realpath($path); // needed for substr() later
            $file = realpath($file);
        }

        // The substr() check added to make sure that the realpath() results in a directory registered so that
        // non-registered directories are not accessible via directory traversal attempts.
        if (file_exists($file) && substr($file, 0, strlen($path)) == $path) {
            $result = $file;
        }

        return $result;
    }
}