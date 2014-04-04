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
 * Translator Cache Catalogue
 *
 * @author  Arunas Mazeika <https://github.com/arunasmazeika>
 * @package Nooku\Library\Translator
 */
class TranslatorCatalogueCache extends TranslatorCatalogue
{
    protected $_namespace;

    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_namespace = $config->namespace;

        if (!extension_loaded('apc'))
        {
            throw new \RuntimeException('APC is not loaded');
        }

        $this->_data = (array) $this->_getFromRegistry('translations');
    }

    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array('namespace' => 'nooku'));
        parent::_initialize($config);
    }

    public function load($translations, $override = false)
    {
        $result = parent::load($translations, $override);

        if ($result)
        {
            $result = $this->_setInRegistry($this->_getRegistryKey('translations'), $this->toArray());
        }

        return $result;
    }

    /**
     * Registry data getter.
     *
     * Gets data from the registry given a registry name.
     *
     * @param string $name The registry name.
     *
     * @return mixed
     */
    protected function _getFromRegistry($name)
    {
        $data = null;

        $key = $this->_getRegistryKey($name);

        if ($this->_isInRegistry($key))
        {
           $data = $this->_fetchFromRegistry($key);
        }

        return $data;
    }

    /**
     * Fetches data from the registry.
     *
     * @param string $key The registry key.
     *
     * @return mixed The data from the registry.
     */
    protected function _fetchFromRegistry($key)
    {
        return unserialize(apc_fetch($key));
    }

    /**
     * Checks is a given key is present in the registry.
     *
     * @param string $key The registry key.
     *
     * @return bool True if exists, false otherwise.
     */
    protected function _isInRegistry($key)
    {
        return apc_exists($key);
    }

    /**
     * Sets data in the registry.
     *
     * @param string $key   The registry key.
     * @param mixed  $value The registry value.
     *
     * @return bool True on success, false otherwise.
     */
    protected function _setInRegistry($key, $value)
    {
        return apc_store($key, serialize($value));
    }

    /**
     * Registry key getter.
     *
     * @param $name The name of the registry.
     *
     * @return string The registry key.
     */
    protected function _getRegistryKey($name)
    {
        return $this->_namespace . '-' . $name . '-' . $this->getIdentifier();
    }
}
