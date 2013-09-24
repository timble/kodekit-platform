<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright      Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Application;

use Nooku\Library;

/**
 * Translator Catalogue
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Koowa
 */
class TranslatorCatalogue extends Library\TranslatorCatalogue implements TranslatorCatalogueInterface
{
    /**
     * @see TranslatorCatalogueInterface::import()
     */
    public function import($translations, $override = false)
    {
        if ($override)
        {
            $this->_data = array_merge($this->_data, $translations);
        }
        else
        {
            $this->_data = array_merge($translations, $this->_data);
        }
    }

    /**
     * @see TranslatorCatalogueInterface::hasKey()
     */
    public function hasKey($key)
    {
        return isset($this->_data[$key]);
    }
}