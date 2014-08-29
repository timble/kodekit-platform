<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Identifier Template Locator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Template\Locator\Identifier
 */
abstract class TemplateLocatorIdentifier extends TemplateLocatorAbstract
{
    /**
     * Locate the template
     *
     * @param  string $url   The template url
     * @throws \RuntimeException If the no base path exists while trying to locate a partial.
     * @return string|false The real template path or FALSE if the template could not be found
     */
    public function locate($url)
    {
        if(!isset($this->_locations[$url]))
        {
            $engines = $this->getObject('template.engine.factory')->getFileTypes();

            //Set defaults
            $path   = null;
            $file   = null;
            $format = null;
            $type   = null;

            //Qualify partial templates.
            if(strpos($url, ':') === false)
            {
                $base = $this->getBasePath();
                if(empty($base)) {
                    throw new \RuntimeException('Cannot qualify partial template path');
                }

                /**
                 * Parse identifiers in following formats :
                 *
                 * - '[file.[format].[type]';
                 * - '[file].[format];
                 */

                $identifier = $this->getIdentifier($base);
                $path       = $identifier->getPath();

                array_pop($path);

                $parts = explode('.', $url);

                if(in_array(end($parts), $engines)) {
                    $type = array_pop($parts);
                }

                $format = array_pop($parts);
                $file   = array_pop($parts);
            }
            else
            {
                /**
                 * Parse identifiers in following formats :
                 *
                 * - '[type]:[package].[path].[file].[format].[type]';
                 * - '[type]:[package].[path].[file].[format];
                 */

                $identifier = $this->getIdentifier($url);
                $path       = $identifier->getPath();

                if(in_array($identifier->name, $engines))
                {
                    $type  = $identifier->getName();
                    $format = array_pop($path);
                    $file   = array_pop($path);
                }
                else
                {
                    $format = $identifier->getName();
                    $file   = array_pop($path);
                }
            }

            $info = array(
                'url'      => $url,
                'domain'   => $identifier->getDomain(),
                'package'  => $identifier->getPackage(),
                'path'     => $path,
                'file'     => $file,
                'format'   => $format,
                'type'     => $type,
            );

            $this->_locations[$url] = $this->find($info);
        }

        return $this->_locations[$url];
    }
}
