<?php
class ComPagesDatabaseBehaviorAssignable extends KDatabaseBehaviorAbstract
{
    protected function _afterTableInsert(KCommandContext $context)
    {
        if($context->affected !== false) {
            $this->_assign($context);
        }
    }

    protected function _afterTableUpdate(KCommandContext $context)
    {
        if($context->affected !== false) {
            $this->_assign($context);
        }
    }

    protected function _afterTableDelete(KCommandContext $context)
    {
        if($context->data->getStatus() == KDatabase::STATUS_DELETED)
        {
            $this->getService('com://admin/pages.database.table.modules')
                ->select(array('pages_page_id' => $context->data->id))
                ->delete();
        }
    }

    protected function _assign(KCommandContext $context)
    {
        if($context->data->modules)
        {
            $pages = $this->getService('com://admin/pages.database.table.pages')
                ->select($this->getService('koowa:database.query.select'));

            $relations = $this->getService('com://admin/pages.database.table.modules')
                ->select($this->getService('koowa:database.query.select'));

            foreach($context->data->modules as $id => $assignments)
            {
                $data = array();
                $rel_current = $relations->find(array('modules_module_id' => $id));

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
                                        $data[] = array('modules_module_id' => $id, 'pages_page_id' => $page->id);
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
                                        $data[] = array('modules_module_id' => $id, 'pages_page_id' => $other);
                                    }
                                }
                                break;
                        }
                    }
                    else
                    {
                        // If relation is set to all, add all pages except current.
                        if(count($rel_current) == 1 && $rel_current->top()->pages_page_id == 0)
                        {
                            $rel_current->delete();
                            foreach($pages as $page)
                            {
                                if($page->id != $context->data->id) {
                                    $data[] = array('modules_module_id' => $id, 'pages_page_id' => $page->id);
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
                                $data[] = array('modules_module_id' => $id, 'pages_page_id' => 0);
                                break;
                            // Add only the current page.
                            case 'none':
                                $data[] = array('modules_module_id' => $id, 'pages_page_id' => $context->data->id);
                                break;
                            // Add selected pages and the current one.
                            default:
                                $others = json_decode($assignments['others']);
                                foreach($others as $other) {
                                    $data[] = array('modules_module_id' => $id, 'pages_page_id' => $other);
                                }

                                if(!in_array($context->data->id, $others)) {
                                    $data[] = array('modules_module_id' => $id, 'pages_page_id' => $context->data->id);
                                }
                                break;
                        }
                    }
                    else
                    {
                        // If nothing is set or current page is not set and relations is not set to all, add current.
                        if(!count($rel_current) || !in_array($context->data->id, $rel_current->pages_page_id) && $rel_current->top()->pages_page_id != 0) {
                            $data[] = array('modules_module_id' => $id, 'pages_page_id' => $context->data->id);
                        }
                    }
                }

                if($data)
                {
                    $rel_current->reset();
                    $rel_current->addData($data)->save();
                }
            }
        }
    }
}
