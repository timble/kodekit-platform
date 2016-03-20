<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-files for the canonical source repository
 */

namespace Kodekit\Component\Files;

use Kodekit\Library;

/**
 * Container Model Entity
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
 */
class ModelEntityContainer extends Library\ModelEntityRow
{
    public function setPropertyPath($value)
    {
        // Prepend with base path if it is a relative path
        if (!preg_match('#^(?:[a-z]\:|~*/)#i', $value)) {
            $value = $this->base_path . '/' . $value;
        }

        return rtrim(str_replace('\\', '/', $value), '\\');
    }

    public function getPropertyBasePath()
    {
        $site = $this->getObject('application')->getSite();
        $path = APPLICATION_ROOT.'/sites/'. $site . '/files';

        return $path;
    }

    public function getPropertyRelativePath()
    {
        $path = $this->path;
        $root = str_replace('\\', '/', $this->base_path);

        return str_replace($root.'/', '', $path);
    }

    public function getAdapter($type, array $config = array())
    {
        return $this->getObject('com:files.adapter.' . $type, $config);
    }

    public function toArray()
    {
        $data = parent::toArray();
        $data['path']          = $this->relative_path;
        $data['relative_path'] = $this->relative_path;

        return $data;
    }
}