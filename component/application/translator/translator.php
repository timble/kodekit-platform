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
            'cache_enabled' => $this->getObject('application')->getCfg('caching'),
            'paths'         => array()
        ));

        parent::_initialize($config);
    }

    public function import($component)
    {
        $paths = $this->getConfig()->paths;

        foreach ($paths as $path)
        {
            $path .= "/component/{$component}/resources/language/";

            if (($file = $this->find($path)) && !$this->load($file, true)) {
                throw new \RuntimeException('Unable to load translations from .' . $file);
            }
        }

        return $this;
    }
}
