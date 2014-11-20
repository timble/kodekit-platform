<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Assignable Database Behavior
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Nooku\Component\Pages
 */
class DatabaseBehaviorAssignable extends Library\DatabaseBehaviorAbstract
{
    protected function _afterInsert(Library\DatabaseContext $context)
    {
        if($context->affected !== false) {
            $this->_assign($context);
        }
    }

    protected function _afterUpdate(Library\DatabaseContext $context)
    {
        if($context->affected !== false) {
            $this->_assign($context);
        }
    }

    protected function _afterDelete(Library\DatabaseContext $context)
    {
        if($context->data->getStatus() == Library\Database::STATUS_DELETED)
        {
            $this->getObject('com:pages.database.table.modules_pages')
                ->select(array('pages_page_id' => $context->data->id))
                ->delete();
        }
    }

    protected function _assign(Library\DatabaseContext $context)
    {
        if($context->data->modules)
        {
            $pages = $this->getObject('com:pages.database.table.pages')
                ->select($this->getObject('lib:database.query.select'));

            $relations = $this->getObject('com:pages.database.table.modules_pages')
                ->select($this->getObject('lib:database.query.select'));

            foreach($context->data->modules as $id => $assignments)
            {
                $data = array();
                $rel_current = $relations->find(array('pages_module_id' => $id));

                // Check if current is checked.
                if(empty($assignments['current']))
                {
                    // Check if others are set.
                    if(!empty($assignments['others']))
                    {
                        // Delete existing relations, they will be overwritten.
                        $rel_current->delete();
                        switch($assignments['others'])
                        {
                            case 'all':
                                // Add all pages except current.
                                foreach($pages as $page)
                                {
                                    if($page->id != $data->id) {
                                        $data[] = array('pages_module_id' => $id, 'pages_page_id' => $page->id);
                                    }
                                }
                                break;
                            case 'none':
                                // Don't add anything.
                                break;
                            default:
                                // Add selected pages except current.
                                $others = json_decode($assignments['others']);
                                foreach($others as $other)
                                {
                                    if($other != $data->id) {
                                        $data[] = array('pages_module_id' => $id, 'pages_page_id' => $other);
                                    }
                                }
                                break;
                        }
                    }
                    else
                    {
                        // If relation is set to all, add all pages except current.
                        if(count($rel_current) == 1 && $rel_current->pages_page_id == 0)
                        {
                            $rel_current->delete();
                            foreach($pages as $page)
                            {
                                if($page->id != $context->data->id) {
                                    $data[] = array('pages_module_id' => $id, 'pages_page_id' => $page->id);
                                }
                            }
                        }
                        // Else if page relation exists, delete it.
                        elseif($current = $rel_current->find(array('pages_page_id' => $context->data->id))) {
                            $current->delete();
                        }
                    }
                }
                else
                {
                    // Check if others are set.
                    if(!empty($assignments['others']))
                    {
                        $rel_current->delete();
                        switch($assignments['others'])
                        {
                            // Set relation to all.
                            case 'all':
                                $data[] = array('pages_module_id' => $id, 'pages_page_id' => 0);
                                break;
                            // Add only the current page.
                            case 'none':
                                $data[] = array('pages_module_id' => $id, 'pages_page_id' => $context->data->id);
                                break;
                            // Add selected pages and the current one.
                            default:
                                $others = json_decode($assignments['others']);
                                foreach($others as $other) {
                                    $data[] = array('pages_module_id' => $id, 'pages_page_id' => $other);
                                }

                                if(!in_array($context->data->id, $others)) {
                                    $data[] = array('pages_module_id' => $id, 'pages_page_id' => $context->data->id);
                                }
                                break;
                        }
                    }
                    else
                    {
                        $pages_page_id = array();
                        foreach($rel_current as $page) {
                            $pages_page_id[] = $page->pages_page_id;
                        }

                        // If nothing is set or current page is not set and relations is not set to all, add current.
                        if(!count($rel_current) || !in_array($context->data->id, $pages_page_id) && $rel_current->pages_page_id != 0) {
                            $data[] = array('pages_module_id' => $id, 'pages_page_id' => $context->data->id);
                        }
                    }
                }

                if($data)
                {
                    $rel_current->reset();
                    $rel_current->create($data);
                    $rel_current->save();
                }
            }
        }
    }
}
