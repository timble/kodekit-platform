<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright   Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link        https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Identifier Translator Locator
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Translator\Locator\Identifier
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
            'url'      => $url,
            'language' => $this->getLanguage(),
            'path'     => '',
            'domain'   => $identifier->getDomain(),
            'package'  => $identifier->getPackage(),
        );

        return $this->find($info);
    }
}
