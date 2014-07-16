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
 * Translator Singleton
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Nooku\Library\Translator
 */
class Translator extends TranslatorAbstract implements ObjectInstantiable, ObjectSingleton
{
    /**
     * Force creation of a singleton
     *
     * @param 	ObjectConfig            $config	  A ObjectConfig object with configuration options
     * @param 	ObjectManagerInterface	$manager  A ObjectInterface object
     * @return DispatcherRequest
     */
    public static function getInstance(ObjectConfig $config, ObjectManagerInterface $manager)
    {
        if (!$manager->isRegistered('translator'))
        {
            $class     = $manager->getClass($config->object_identifier);
            $instance  = new $class($config);
            $manager->setObject($config->object_identifier, $instance);

            $manager->registerAlias($config->object_identifier, 'translator');
        }

        return $manager->getObject('translator');
    }

    /**
     * Imports translations from a file.
     *
     * @param string $file The translations file path.
     *
     * @return TranslatorInterface
     */
    public function import($file)
    {
        $catalogue = $this->getCatalogue();

        if (!$catalogue->isLoaded($file) && $this->load($file, true)) {
            $catalogue->setLoaded($file);
        }

        return $this;
    }
}
