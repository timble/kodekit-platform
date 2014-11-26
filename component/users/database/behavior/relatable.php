<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Relatable Database Behavior
 *
 * Takes care of creating N:N relationships given an item value and a collection of values.
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Nooku\Component\Users
 */
class DatabaseBehaviorRelatable extends Library\DatabaseBehaviorAbstract
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'table'   => 'users_groups_users',
            'columns' => array('collection' => 'users_group_id', 'item' => 'users_user_id'),
            'values'  => 'groups'
        ));

        parent::_initialize($config);
    }

    protected function _afterUpdate(Library\DatabaseContextInterface $context)
    {
        // Same as insert.
        return $this->_afterInsert($context);
    }

    protected function _afterInsert(Library\DatabaseContextInterface $context)
    {
        $data   = $context->data;
        $config = $this->getConfig();

        if (isset($data->{$config->values}) && $data->getStatus() != Library\Database::STATUS_FAILED)
        {
            if ($groups = $data->{$config->values}) {
                $this->_insertGroups($groups, $context);
            }

            $this->_cleanup($groups, $context);

            // Unset data for avoiding un-necessary queries on subsequent saves.
            unset($data->{$config->values});
        }
    }

    protected function _insertGroups($groups, Library\DatabaseContextInterface $context)
    {
        $groups = (array) $groups;

        $config = $this->getConfig();

        $query = $this->getObject('lib:database.query.insert')
                      ->table($config->table)
                      ->columns(array($config->columns->item, $config->columns->collection));

        foreach ($groups as $group) {
            $query->values(array($this->_getItemValue($context), $group));
        }

        // Just ignore duplicate entries.
        $query = str_replace('INSERT', 'INSERT IGNORE', (string) $query);

        $context->subject->getAdapter()->execute($query);
    }

    protected function _cleanup($groups, Library\DatabaseContextInterface $context)
    {
        $groups = (array) $groups;

        $config  = $this->getConfig();
        $adapter = $context->getSubject()->getAdapter();

        $query = $this->getObject('lib:database.query.select')
                      ->table($config->table)
                      ->columns(array($config->columns->collection))
                      ->where("{$config->columns->item} = :item")
                      ->bind(array('item' => $this->_getItemValue($context)));

        $current = $adapter->select($query, Library\Database::FETCH_FIELD_LIST);

        $remove = array_diff($current, $groups);

        if (count($remove))
        {
            $query = $this->getObject('lib:database.query.delete')->table($config->table)
                          ->where("{$config->columns->collection} IN :groups")->where("{$config->columns->item} = :item")
                          ->bind(array('groups' => $remove, 'item' => $this->_getItemValue($context)));

            $adapter->execute((string) $query);
        }
    }

    protected function _getItemValue(Library\DatabaseContextInterface $context)
    {
        return $context->data->id;
    }
}