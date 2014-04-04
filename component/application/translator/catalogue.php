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
 * Translator Catalogue Interface
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Koowa
 */
class TranslatorCatalogue extends Library\TranslatorCatalogue implements TranslatorCatalogueInterface
{
    /**
     * List containing sources of loaded translations.
     *
     * @var array
     */
    protected $_loaded;

    public function isLoaded($source)
    {
        return isset($this->_loaded[$source]);
    }

    public function setLoaded($source)
    {
        $this->_loaded[] = $source;
        $this->_loaded = array_unique($this->_loaded);
        return $this;
    }
}