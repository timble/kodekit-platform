<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Sites;

use Nooku\Library;

/**
 * Sites Model
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Sites
 */
class ModelSites extends Library\ModelAbstract implements Library\ObjectMultiton
{	
     public function __construct(Library\ObjectConfig $config)
     {
         parent::__construct($config);
         
         $this->getState()
             ->insert('name'      , 'cmd', null, true)
             ->insert('limit'     , 'int')
             ->insert('offset'    , 'int')
             ->insert('sort'      , 'cmd')
             ->insert('direction' , 'word', 'asc')
             ->insert('search'    , 'string');
    }
    
    public function getRowset()
    {
        if(!isset($this->_rowset))
        {
            $state = $this->getState();
            $data = array();

            //Get the sites
			foreach(new \DirectoryIterator(JPATH_SITES) as $file)
			{
				if($file->isDir() && !(substr($file->getFilename(), 0, 1) == '.')) 
				{
        			$data[] = array(
        				'name' => $file->getFilename()
				    );
    			}
			}

            //Apply state information
            foreach($data as $key => $value)
            {   
                if($state->search)
                {
                     if($value->name != $state->search) {
                         unset($data[$key]);
                      }
                }
            }
                        
            //Set the total
            $this->_total = count($data);
                    
            //Apply limit and offset
            if($state->limit) {
                $data = array_slice($data, $state->offset, $state->limit);
            }
                        
            $this->_rowset = $this->getObject('com:sites.database.rowset.sites', array('data' => $data));
        }
        
        return $this->_rowset;
    }
    
    public function getTotal()
    {
        if(!isset($this->_total)) {
            $this->getRowset();
        }
        
        return $this->_total;
    }
}