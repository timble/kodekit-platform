<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Categories Model Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class ComArticlesModelCategories extends KModelAbstract
{
    public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
		$this->_state
			->insert('published' ,'int', 1)
			->insert('limit'    , 'int')
            ->insert('offset'   , 'int')
            ->insert('sort'     , 'cmd', 'ordering')
            ->insert('direction', 'word', 'asc');
	}
    
    public function getList()
    {
        if(!isset($this->_list))
        {
            $folders  = array(); 
            $children = array();

            $categories = KFactory::tmp('admin::com.categories.model.categories')
                ->published($this->_state->published)
                ->section('com_content')
                ->limit(0)
                ->sort($this->_state->sort)
                ->direction($this->_state->direction)
                ->getList();

            foreach($categories as $category)
            {
                $children[$category->section_id][] = array(
                    'id'	      => $category->id,
                    'title'	      => $category->title,
                    'slug'		  => $category->slug,
                	'description' => $category->description,
                    'enabled'	  => $category->enabled,
                 	'locked_by'	  => $category->locked_by,
                    'locked_on'   => $category->locked_on,    
                    'access'	  => $category->access,
                    'parent_id'   => $category->section_id,
                    'path'		  => '',
                    'type'		  => 'category'
                );
            }

            $sections = KFactory::tmp('admin::com.sections.model.sections')
                ->published($this->_state->published)
                ->scope('content')
                ->limit(0)
                ->sort($this->_state->sort)
                ->direction($this->_state->direction)
                ->getList();

            $count = 0;
            foreach($sections as $section)
            {
                ++$count;
                
                $folders[$count] = array(
                    'id'	      => $section->id,
                    'title'       => $section->title,
                    'slug'		  => $section->slug,
                	'description' => $section->description,
                    'enabled'	  => $section->enabled,
                    'locked_by'	  => $section->locked_by,
                    'locked_on'   => $section->locked_on,    
                    'access'	  => $section->access,
                    'level'       => 0,
	                'parent_id'   => 0,
                    'path'		  => '',
                    'type'		  => 'section',
                    'params'	  => $section->params,
                );
                
                $parent_id = $count;
                
                if(isset($children[$section->id])) 
                {
                    foreach($children[$section->id] as $child) 
                    {
                        ++$count;
                        
                        $child['parent_id'] = $parent_id;
                        $folders[$count]    = $child;
                    } 
                }
            }
            
            //Set the total
			$this->_total = count($folders);
            
            //Apply limit and offset
            if($this->_state->limit) {
				$folders = array_slice( $folders, $this->_state->offset, $this->_state->limit, true);
			}
            
			//Create the paths of each node
			foreach($folders as $key => $folder)
			{
				$path   = array();
				$parent = $folder['parent_id'];
				
				if(!empty($parent)) 
				{
					//Create node path
					$path = $folders[$parent]['path'];
					$id   = $folders[$parent]['id'];
				
					$path[] = $id;
				}

				//Set the node path
				$folders[$key]['path'] = $path;	
			}
			
            $folders = KFactory::tmp('admin::com.articles.database.rowset.folders', array('data' => $folders));
            $this->_list = $folders;
        }

        return $this->_list;
    }
    
    public function getTotal()
	{
		if (!$this->_total) {
			$this->getList();
		}

		return $this->_total;
	}
}