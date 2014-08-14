<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Component Template Locator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\TemplateLoaderComponent
 */
abstract class TemplateLocatorAbstract extends Object implements TemplateLocatorInterface
{
    /**
     * The type
     *
     * @var string
     */
    protected $_type = '';

    /**
     * Get the type
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Locate the template based on a virtual path
     *
     * @param  string $template  Stream path or resource
     * @param  string $base      The base path or resource (used to resolved partials).
     * @throws \RuntimeException If the no base path was passed while trying to locate a partial.
     * @return string   The physical stream path for the template
     */
    public function locate($template, $base = null)
    {
        //Qualify partial templates.
        if(strpos($template, ':') === false)
        {
            if(empty($base)) {
                throw new \RuntimeException('Cannot qualify partial template path');
            }

            $identifier = $this->getIdentifier($base);

            $file    = pathinfo($template, PATHINFO_FILENAME);
            $format  = pathinfo($template, PATHINFO_EXTENSION);
            $path    = $identifier->getPath();

            array_pop($path);
        }
        else
        {
            $identifier = $this->getIdentifier($template);

            $path    = $identifier->getPath();
            $file    = array_pop($path);
            $format  = $identifier->getName();
        }

        $info = array(
            'template' => $template,
            'domain'   => $identifier->getDomain(),
            'package'  => $identifier->getPackage(),
            'path'     => $path,
            'file'     => $file,
            'format'   => $format,
        );

        return $this->find($info);
    }

    /**
     * Get a path from an file
     *
     * Function will check if the path is an alias and return the real file path
     *
     * @param  string $file The file path
     * @return string The real file path
     */
    final public function realPath($file)
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