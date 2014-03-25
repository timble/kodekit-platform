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
    /**
     * A reference to the container configuration
     *
     * @var Library\ModelEntityInterface
     */
    protected $_parameters;

    public function getProperty($name)
    {
        if ($name == 'path' && !empty($this->_data['path']))
        {
            $result = $this->_data['path'];
            // Prepend with site root if it is a relative path
            if (!preg_match('#^(?:[a-z]\:|~*/)#i', $result)) {
                $result = JPATH_FILES . '/' . $result;
            }

            $result = rtrim(str_replace('\\', '/', $result), '\\');

            return $result;
        }

        if ($name == 'relative_path') {
            return $this->_data['path'];
        }

        if ($name == 'path_value') {
            return $this->_data['path'];
        }

        if ($name == 'parameters' && !is_object($this->_data['parameters'])) {
            return $this->getParameters();
        }

        return parent::getProperty($name);
    }

    public function getParameters()
    {
        if (empty($this->_parameters))
        {
            $this->_parameters = $this->getObject('com:files.model.entity.config')
                ->setProperties(json_decode($this->_data['parameters'], true));
        }

        return $this->_parameters;
    }

    public function toArray()
    {
        $data = parent::toArray();

        $data['path']          = $this->path_value;
        $data['parameters']    = $this->parameters->toArray();
        $data['relative_path'] = $this->relative_path;

        return $data;
    }

    public function getProperties($modified = false)
    {
        $data = parent::getProperties($modified);

        if (isset($data['parameters'])) {
            $data['parameters'] = $this->parameters->getProperties();
        }

        return $data;
    }

    public function getAdapter($type, array $config = array())
    {
        return $this->getObject('com:files.adapter.' . $type, $config);
    }
}