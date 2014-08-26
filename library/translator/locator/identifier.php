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
 * Identifier Translator Locator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Translator\Locator\Identifier
 */
abstract class TranslatorLocatorIdentifier extends TranslatorLocatorAbstract
{
    /**
     * Locate the translation based on a physical path
     *
     * @param  string $url       The translation url
     * @return string  The real file path for the translation
     */
    public function locate($url)
    {
        $identifier = $this->getIdentifier($url);

        $info   = array(
            'url'     => $url,
            'locale'  => $this->getLocale(),
            'path'    => '',
            'domain'  => $identifier->getDomain(),
            'package' => $identifier->getPackage(),
        );

        return $this->find($info);
    }
}
