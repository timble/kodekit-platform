<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Application;

use Nooku\Library;

/**
 * Translator Cache Catalogue
 *
 * @author  Arunas Mazeika <https://github.com/arunasmazeika>
 * @package Nooku\Library\Translator
 */
class TranslatorCatalogueCache extends Library\TranslatorCatalogueCache implements TranslatorCatalogueInterface
{
    /**
     * List containing sources of loaded translations.
     *
     * @var array
     */
    protected $_loaded;

    public function isLoaded($source)
    {
        if (!isset($this->_loaded))
        {
            $loaded        = $this->_getFromRegistry('loaded');
            $this->_loaded = (array) $loaded;
        }

        return in_array($source, $this->_loaded);
    }

    public function setLoaded($source)
    {
        $this->_loaded[] = $source;
        $this->_loaded   = array_unique($this->_loaded);
        $this->_setInRegistry($this->_getRegistryKey('loaded'), $this->_loaded);
        return $this;
    }
}
