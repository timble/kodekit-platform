<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-application for the canonical source repository
 */

namespace Kodekit\Component\Application;

use Kodekit\Library;

/**
 * Sites Model
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Application
 */
class ModelSites extends Library\ModelAbstract
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('name', 'cmd', null, true)
            ->insert('limit', 'int')
            ->insert('offset', 'int')
            ->insert('sort', 'cmd')
            ->insert('search', 'string');
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'identity_key' => 'name',
        ));

        parent::_initialize($config);
    }

    protected function _actionFetch(Library\ModelContext $context)
    {
        $state = $context->state;
        $sites  = array();

        //Get the sites
        foreach (new \DirectoryIterator(\Kodekit::getInstance()->getRootPath().'/sites') as $file)
        {
            if ($file->isDir() && !(substr($file->getFilename(), 0, 1) == '.'))
            {
                $sites[] = array(
                    'name' => $file->getFilename()
                );
            }
        }

        //Apply state information
        foreach ($sites as $key => $value)
        {
            if ($state->search)
            {
                if ($value->name != $state->search) {
                    unset($sites[$key]);
                }
            }
        }

        //Set the total
        $this->_count = count($sites);

        //Apply limit and offset
        if ($state->limit) {
            $sites = array_slice($sites, $state->offset, $state->limit);
        }

        $entity = parent::_actionFetch($context);

        foreach($sites as $site) {
            $entity->insert($site);
        }

        return $entity;
    }

    protected function _actionCount(Library\ModelContext $context)
    {
        if (!isset($this->_count)) {
            $this->fetch();
        }

        return $this->_count;
    }
}