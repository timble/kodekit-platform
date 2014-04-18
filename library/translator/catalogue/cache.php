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
    /**
     * Catalogue registry.
     *
     * @var TranslatorCatalogueRegistryInterface
     */
    protected $_registry;

    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_registry  = $config->registry;

        $registry = $this->getRegistry();

        // Load registry data.
        if ($registry->has('translations')) $this->_data = (array) $registry->get('translations');
        if ($registry->has('loaded')) $this->_loaded = (array) $registry->get('loaded');
    }

    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array('registry' => 'lib:translator.catalogue.registry.apc'));
        parent::_initialize($config);
    }

    /**
     * Registry setter.
     *
     * @param TranslatorCatalogueRegistryInterface $handler The registry object.
     *
     * @return TranslatorCatalogueInterface
     */
    public function setRegistry(TranslatorCatalogueRegistryInterface $registry)
    {
        $this->_registry = $registry;
    }

    /**
     * Registry getter.
     *
     * @return TranslatorCatalogueRegistryInterface The registry object.
     */
    public function getRegistry()
    {
        if (!$this->_registry instanceof TranslatorCatalogueRegistryInterface)
        {
            $registry = $this->getObject($this->_registry);
            $this->setRegistry($registry);
        }

        return $this->_registry;
    }

    public function load($translations, $override = false)
    {
        $result = parent::load($translations, $override);

        if ($result)
        {
            $result = $this->getRegistry()->set('translations', $this->toArray());
        }

        return $result;
    }

    public function setLoaded($source)
    {
        parent::setLoaded($source);
        $this->getRegistry()->set('loaded', $this->_loaded);
        return $this;
    }


}
