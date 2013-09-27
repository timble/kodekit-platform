<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Script;

use Symfony\Component\Yaml;

class TranslationsStrategyYaml implements TranslationsStrategyInterface
{
    public function dump($data)
    {
        $dumper = new Yaml\Dumper();
        return $dumper->dump($this->_getAssoc($data), 2);
    }

    public function parse($file)
    {
        $parser = new Yaml\Parser();
        return $parser->parse(file_get_contents($file));
    }

    protected function _getAssoc($data)
    {
        $assoc = array();

        foreach ($data as $key => $value)
        {
            $assoc[$key] = $value['value'];
        }

        return $assoc;
    }

    public function getFileExtension()
    {
        return 'yml';
    }
}