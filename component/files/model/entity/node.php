<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Node Model Entity
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
 */
class ModelEntityNode extends Library\ModelEntityAbstract
{
    /**
     * The file adapter
     *
     * @var AdapterInterface
     */
    protected $_adapter;

    /**
     * The file container
     *
     * @var string
     */
    protected $_container;

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->mixin('lib:behavior.mixin', $config);

        if ($config->validator !== false)
        {
            if ($config->validator === true) {
                $config->validator = 'com:files.database.validator.' . $this->getIdentifier()->name;
            }

            $this->addCommandHandler($this->getObject($config->validator));
        }
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'validator' => true
        ));

        parent::_initialize($config);
    }

    public function copy()
    {
        $context         = $this->getContext();
        $context->result = false;

        if ($this->invokeCommand('before.copy', $context) !== false)
        {
            $context->result = $this->_adapter->copy($this->destination_fullpath);
            $this->invokeCommand('after.copy', $context);
        }

        if ($context->result !== false)
        {
            if ($this->destination_folder) {
                $this->folder = $this->destination_folder;
            }
            if ($this->destination_name) {
                $this->name = $this->destination_name;
            }

            $this->setStatus($this->overwritten ? self::STATUS_UPDATED : self::STATUS_CREATED);
        }
        else $this->setStatus(self::STATUS_FAILED);

        return $context->result;
    }

    public function move()
    {
        $context         = $this->getContext();
        $context->result = false;

        if ($this->invokeCommand('before.move', $context) !== false)
        {
            $context->result = $this->_adapter->move($this->destination_fullpath);
            $this->invokeCommand('after.move', $context);
        }

        if ($context->result !== false)
        {
            if ($this->destination_folder) {
                $this->folder = $this->destination_folder;
            }

            if ($this->destination_name) {
                $this->name = $this->destination_name;
            }

            $this->setStatus($this->overwritten ? self::STATUS_UPDATED : self::STATUS_CREATED);
        }
        else $this->setStatus(self::STATUS_FAILED);

        return $context->result;
    }

    public function delete()
    {
        $context         = $this->getContext();
        $context->result = false;

        if ($this->invokeCommand('before.delete', $context) !== false)
        {
            $context->result = $this->_adapter->delete();
            $this->invokeCommand('after.delete', $context);
        }

        if ($context->result === false) {
            $this->setStatus(self::STATUS_FAILED);
        } else {
            $this->setStatus(self::STATUS_DELETED);
        }

        return $context->result;
    }

    public function getPropertyFullpath()
    {
        return $this->_adapter->getRealPath();
    }

    public function getPropertyPath()
    {
        return trim(($this->folder ? $this->folder . '/' : '') . $this->name, '/\\');
    }

    public function getPropertyDisplayName()
    {
        return $this->name;
    }

    public function getPropertyDestinationPath()
    {
        $folder = !empty($this->destination_folder) ? $this->destination_folder . '/' : (!empty($this->folder) ? $this->folder . '/' : '');
        $name   = !empty($this->destination_name) ? $this->destination_name : $this->name;

        return trim($folder . $name, '/\\');
    }

    public function getPropertyDestinationFullpath()
    {
        return $this->container->path . '/' . $this->destination_path;
    }

    public function getPropertyAdapter()
    {
        return $this->_adapter;
    }

    public function setProperty($name, $value, $modified = true)
    {
        parent::setProperty($name, $value, $modified);

        if (in_array($name, array('container', 'folder', 'name'))) {
            $this->setAdapter();
        }
    }

    public function getContainer()
    {
        if (!isset($this->_container))
        {
            //Set the container
            $container = $this->getObject('com:files.model.containers')->slug($this->container)->fetch();

            if (!is_object($container) || $container->isNew()) {
                throw new \UnexpectedValueException('Invalid container');
            }

            $this->_container = $container;
        }

        return $this->_container;
    }

    public function getContext()
    {
        $context = new Library\DatabaseContext();
        $context->setSubject($this);

        return $context;
    }

    public function setAdapter()
    {
        $type      = $this->getIdentifier()->name;
        $container = $this->getContainer();

        $this->_adapter = $container->getAdapter($type, array(
            'path' => $container->path . '/' . ($this->folder ? $this->folder . '/' : '') . $this->name
        ));

        unset($this->_data['fullpath']);
        unset($this->_data['metadata']);

        return $this;
    }

    public function isLockable()
    {
        return false;
    }

    public function isNew()
    {
        return empty($this->name) || !$this->_adapter->exists();
    }

    public function toArray()
    {
        $data = parent::toArray();

        unset($data['csrf_token']);
        unset($data['action']);
        unset($data['component']);
        unset($data['format']);
        unset($data['view']);

        $data['container'] = $this->getContainer()->slug;
        $data['type']      = $this->getIdentifier()->name;

        return $data;
    }
}
