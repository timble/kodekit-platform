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
 * Abstract Translator Catalogue Registry
 *
 * @author  Arunas Mazeika <https://github.com/arunasmazeika>
 * @package Nooku\Library\Translator
 */
abstract class TranslatorCatalogueRegistryAbstract extends Object implements TranslatorCatalogueRegistryInterface
{
    protected $_namespace;

    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->setNamespace($config->namespace);
    }

    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array('namespace' => 'nooku.translator.catalogue.registry'));
        parent::_initialize($config);
    }

    public function setNamespace($namespace)
    {
        $this->_namespace = $namespace;
        return $this;
    }

    public function getNamespace()
    {
        return $this->_namespace;
    }
}