<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright      Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Container Model Entity
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class ModelEntityContainer extends Library\ModelEntityRow
{
    public function setPropertyPath($value)
    {
        // Prepend with site root if it is a relative path
        if (!preg_match('#^(?:[a-z]\:|~*/)#i', $value)) {
            $value = JPATH_FILES . '/' . $value;
        }

        return rtrim(str_replace('\\', '/', $value), '\\');
    }

    public function getPropertyRelativePath()
    {
        $path = $this->path;
        $root = str_replace('\\', '/', JPATH_FILES);

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