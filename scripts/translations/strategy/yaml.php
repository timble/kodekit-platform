<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Script;

class TranslationsStrategyYaml implements TranslationsStrategyInterface
{
    public function dump($data)
    {
        return yaml_emit($this->_getAssoc($data));
    }

    public function parse($file)
    {
        return yaml_parse_file($file);
    }

    protected function _getAssoc($data)
    {
        $assoc = array();

        foreach ($data as $key => $value)
        {
            if (is_array($value))
            {
                $assoc[$key] = $value['value'];
            }
            else
            {
                $assoc[$key] = $value;
            }
        }

        return $assoc;
    }

    public function getFileExtension()
    {
        return 'yaml';
    }
}