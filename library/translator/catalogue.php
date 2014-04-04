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
 * Translator Catalogue
 *
 * @author  Arunas Mazeika <https://github.com/arunasmazeika>
 * @package Nooku\Library\Translator
 */
class TranslatorCatalogue extends ObjectArray implements TranslatorCatalogueInterface
{
    /**
     * List containing sources of loaded translations.
     *
     * @var array
     */
    protected $_loaded;

    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_loaded = array();
    }

    public function isLoaded($source)
    {
        return in_array($source, $this->_loaded);
    }

    public function setLoaded($source)
    {
        $this->_loaded[] = $source;
        $this->_loaded   = array_unique($this->_loaded);
        return $this;
    }

    public function load($translations, $override = false)
    {
        if ($override)
        {
            $this->_data = array_merge($this->_data, $translations);
        }
        else
        {
            $this->_data = array_merge($translations, $this->_data);
        }

        return true;
    }

    public function hasString($string)
    {
        return isset($this->_data[$string]);
    }
}
