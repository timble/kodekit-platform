<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Groups Database Rowset
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Cache
 */
 
class CacheDatabaseRowsetGroups extends Library\DatabaseRowsetAbstract
{	
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'identity_column'   => 'name'
        ));

        parent::_initialize($config);
    }
    
	/**
	 * Get a value by key
	 *
	 * @param   string  The key name.
	 * @return  string  The corresponding value.
	 */
	public function __get($column)
	{
	    $result = null;
	    
	    if($column == 'count') 
		{
            $result = 0;
		    foreach($this as $row) {
		        $result += $row->count;
            }
        }

	    if($column == 'size' && empty($this->_data['size'])) 
		{
		    $result = 0;
		    foreach($this as $row) {
		        $result += $row->size;
            }
        }
	   
		return $result;
	}
}