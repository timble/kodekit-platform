<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Translator Cache Catalogue
 *
 * @author  Arunas Mazeika <https://github.com/arunasmazeika>
 * @package Nooku\Library\Translator
 */
class TranslatorCatalogueCache extends TranslatorCatalogueAbstract
{
    /**
     * The registry cache namespace
     *
     * @var boolean
     */
    protected $_namespace = 'nooku';

    /**
     * Constructor
     *
     * @param ObjectConfig  $config  A ObjectConfig object with optional configuration options
     * @throws \RuntimeException    If the APC PHP extension is not enabled or available
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        if (!extension_loaded('apc')) {
            throw new \RuntimeException('APC is not loaded');
        }
    }

    /**
     * Get the registry cache namespace
     *
     * @param string $namespace
     * @return void
     */
    public function setNamespace($namespace)
    {
        $this->_namespace = $namespace;
    }

    /**
     * Get the registry cache namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->_namespace;
    }

    /**
     * Get an item from the array by offset
     *
     * @param   int     $offset The offset
     * @return  mixed   The item from the array
     */
    public function offsetGet($offset)
    {
        if(!parent::offsetExists($offset))
        {
            if($result = apc_fetch($this->getNamespace().'-translator-'.$offset))
            {
                $result =  unserialize($result);
                parent::offsetSet($offset, $result);
            }
        }
        else $result = parent::offsetGet($offset);

        return $result;
    }

    /**
     * Set an item in the array
     *
     * @param   int     $offset The offset of the item
     * @param   mixed   $value  The item's value
     * @return  object  ObjectRegistryCache
     */
    public function offsetSet($offset, $value)
    {
        if($value instanceof ObjectIdentifierInterface) {
            apc_store($this->getNamespace().'-translator-'.$offset, serialize($value));
        }

        parent::offsetSet($offset, $value);
    }

    /**
     * Check if the offset exists
     *
     * @param   int     $offset The offset
     * @return  bool
     */
    public function offsetExists($offset)
    {
        if(false === $result = parent::offsetExists($offset)) {
            $result = apc_exists($this->getNamespace().'-translator-'.$offset);
        }

        return $result;
    }

    /**
     * Unset an item from the array
     *
     * @param   int     $offset
     * @return  void
     */
    public function offsetUnset($offset)
    {
        apc_delete($this->getNamespace().'-translator-'.$offset);
        parent::offsetUnset($offset);
    }

    /**
     * Clears APC cache
     *
     * @return $this
     */
    public function clear()
    {
        // Clear user cache
        apc_clear_cache('user');

        return parent::clear();
    }
}
