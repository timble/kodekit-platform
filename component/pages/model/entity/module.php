<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Module Model Entity
 *
 * @author  Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @package Nooku\Component\Pages
 */
class ModelEntityModule extends Library\ModelEntityRow
{
    /**
     * The user parameters
     *
     * @var \JParameter
     */
    protected $_parameters;

    public function getPropertyDescription()
    {
        if($this->manifest instanceof \SimpleXMLElement) {
            return $this->manifest->description;
        }

        return null;
    }

    public function getPropertyIdentifier()
    {
        $module  = $this->name;
        $package = $this->component;

        return $this->getIdentifier('com://site/'.$package.'.module.'.$module.'.html');
    }

    public function getPropertyManifest()
    {
        $class = $this->getObject('manager')->getClass($this->identifier, false);
        $path  = dirname($this->getObject('manager')->getClassLoader()->getPath($class, 'site'));
        $file  = $path.'/'.basename($path).'.xml';

        if(file_exists($file)) {
            $result = simplexml_load_file($file);
        } else {
            $result = '';
        }

        return $result;
    }

    public function getPropertyPages()
    {
        if(!$this->isNew())
        {
            $table = $this->getObject('com:pages.database.table.modules_pages');
            $query = $this->getObject('lib:database.query.select')
                ->columns('pages_page_id')
                ->where('pages_module_id = :id')
                ->bind(array('id' => $this->id));

            $pages = $table->select($query, Library\Database::FETCH_FIELD_LIST);

            if(count($pages) == 1 && $pages[0] == 0) {
                $pages = 'all';
            }

            if(!$pages) {
                $pages = 'none';
            }
        }
        else $pages = 'all';

        return $pages;
    }

    public function getParameters()
    {
        if (empty($this->_parameters))
        {
            $class = $this->getObject('manager')->getClass($this->identifier, false);
            $path = dirname($this->getObject('manager')->getClassLoader()->getPath($class));
            $file = $path.'/config.xml';

            $this->_parameters = new \JParameter( $this->parameters, $file, 'module' );
        }

        return $this->_parameters;
    }

	/**
	 * Saves the row to the database.
	 *
	 * This performs an intelligent insert/update and reloads the properties with fresh data from the table on success.
	 *
	 * @return boolean	If successfull return TRUE, otherwise FALSE
	 */
	public function save()
	{
		$result = parent::save();

		if($this->isModified('pages'))
		{
		    $table = $this->getObject('com:pages.database.table.modules');
		
		    //Clean up existing assignemnts
		    $table->select(array('pages_module_id' => $this->id))->delete();

		    if(is_array($this->pages)) 
		    {
                foreach($this->pages as $page)
			    {
				    $table->select(null, Library\Database::FETCH_ROW)
					       ->setProperties(array(
							    'pages_module_id' => $this->id,
							    'pages_page_id'   => $page
				    	   ))->save();
			    }

		    } 
		    elseif($this->pages == 'all') 
		    {
                $table->select(null, Library\Database::FETCH_ROW)
				       ->setProperties(array(
						    'moduleid'	=> $this->id,
						    'menuid'	=> 0
			    	    ))->save();
		    }
		}
													
		return $result;
    }

	/**
     * Return an associative array of the data.
     *
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();

        $data['title']       = (string) $this->title;
        $date['description'] = (string) $this->description;
        return $data;
    }
}