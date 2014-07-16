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
 * Translator
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Application
 */
class Translator extends Library\Translator
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'caching'  => $this->getObject('application')->getCfg('caching'),
            'options'  => array('paths' => array())
        ))->append(array(
            'catalogue' => 'com:application.translator.catalogue' . ($config->caching ? '.cache' : '')
        ));

        parent::_initialize($config);
    }

    public function import($component)
    {
        $catalogue = $this->getCatalogue();

        // Append current locale to source.
        $source = 'com:' . $component . '.' . $this->getLocale();

        if (!$catalogue->isLoaded($source))
        {
            $paths = $this->getConfig()->options->paths;

            foreach ($paths as $path)
            {
                $path .= "/component/{$component}/resources/language/";

                if (($file = $this->find($path)) && !$this->load($file, true)) {
                    throw new \RuntimeException('Unable to load translations from .' . $file);
                }
            }

            // Set component as loaded.
            $catalogue->setLoaded($source);
        }

        return $this;
    }
}
