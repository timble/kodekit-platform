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
     * @return string   The physical path of the template
     */
    public function locate($url)
    {
        if(!isset($this->_locations[$url]))
        {
            //Qualify partial templates.
            if(strpos($url, ':') === false)
            {
                $base = $this->getBasePath();
                if(empty($base)) {
                    throw new \RuntimeException('Cannot qualify partial template path');
                }

                $identifier = $this->getIdentifier($base);

                $file    = pathinfo($url, PATHINFO_FILENAME);
                $format  = pathinfo($url, PATHINFO_EXTENSION);
                $path    = $identifier->getPath();

                array_pop($path);
            }
            else
            {
                $identifier = $this->getIdentifier($url);

                $path    = $identifier->getPath();
                $file    = array_pop($path);
                $format  = $identifier->getName();
            }

            $info = array(
                'url'      => $url,
                'domain'   => $identifier->getDomain(),
                'package'  => $identifier->getPackage(),
                'path'     => $path,
                'file'     => $file,
                'format'   => $format,
            );

            $this->_locations[$url] = $this->find($info);
        }

        return $this->_locations[$url];
    }
}
