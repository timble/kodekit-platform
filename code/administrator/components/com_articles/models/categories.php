<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
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

class ComArticlesModelCategories extends ComCategoriesModelCategories
{
    protected $_folders;

    public function __construct(KConfig $config) {
        parent::__construct($config);

        $state = $this->getState();
        $state->insert('aid', 'int');
    }

    protected function _initialize(KConfig $config) {
        $config->append(array('table' => 'com://admin/categories.database.table.categories'));
        parent::_initialize($config);
    }

    protected function _buildQuerWhere(KDatabaseQuerySelect $query) {
        $state = $this->getState();

        if (is_numeric($aid = $state->aid)) {
            $query->where('tbl.access <= :aid')->bind(array('aid' => $aid));
        }

        parent::_buildQueryWhere($query);
    }

    public function getFolders()
    {
        if(!isset($this->_folders))
        {
            $state = $this->getState();
            
            $folders  = array(); 
            $children = array();

            $categories = $this->getService('com://admin/categories.model.categories')
                ->published($state->published)
                ->section('com_content')
                ->sort($state->sort)
                ->direction($state->direction)
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

            $sections = $this->getService('com://admin/articles.model.sections')
                ->published($state->published)
                ->sort($state->sort)
                ->direction($state->direction)
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
            if($state->limit) {
				$folders = array_slice( $folders, $state->offset, $state->limit, true);
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
			
            $folders = $this->getService('com://admin/articles.database.rowset.folders', array('data' => $folders));
            $this->_folders = $folders;
        }

        return $this->_folders;
    }
}