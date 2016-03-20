<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright   Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link        https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Script;

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
            if (is_array($value)) {
                $assoc[$key] = $value['value'];
            } else {
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