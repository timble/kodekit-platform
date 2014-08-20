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
     * @param  string $base  The base url or resource (used to resolved partials).
     * @throws \RuntimeException If the no base path was passed while trying to locate a partial.
     * @return string   The physical path of the template
     */
    public function locate($url, $base = null)
    {
        //Qualify partial templates.
        if(strpos($url, ':') === false)
        {
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

        return $this->find($info);
    }
}
