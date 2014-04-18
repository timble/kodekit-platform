<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright      Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Translator Catalogue APC Registry
 *
 * @author  Arunas Mazeika <https://github.com/arunasmazeika>
 * @package Nooku\Library\Translator
 */

class TranslatorCatalogueRegistryApc extends TranslatorCatalogueRegistryAbstract
{
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        if (!extension_loaded('apc'))
        {
            throw new \RuntimeException('APC is not loaded');
        }
    }

    public function has($key)
    {
        return apc_exists($this->getNamespace() . '-' . $key);
    }

    public function get($key)
    {
        return unserialize(apc_fetch($this->getNamespace() . '-' . $key));
    }

    public function set($key, $value)
    {
        return apc_store($this->getNamespace() . '-' . $key, serialize($value));
    }

    public function remove($key)
    {
        return apc_delete($this->getNamespace() . '-' . $key);
    }

    public function clear()
    {
        return apc_clear_cache();
    }
}