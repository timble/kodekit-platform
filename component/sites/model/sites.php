<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Sites;

use Nooku\Library;

/**
 * Sites Model
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Sites
 */
class ModelSites extends Library\ModelAbstract implements Library\ObjectMultiton
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('name', 'cmd', null, true)
            ->insert('limit', 'int')
            ->insert('offset', 'int')
            ->insert('sort', 'cmd')
            ->insert('direction', 'word', 'asc')
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
        foreach (new \DirectoryIterator(\Nooku::getInstance()->getRootPath().'/sites') as $file)
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
            $entity->create($site);
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