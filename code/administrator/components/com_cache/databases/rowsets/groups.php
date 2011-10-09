<?php
/**
 * @version     $Id: sections.php 592 2011-03-16 00:30:35Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Cache Groups Rowset Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 */
 
class ComCacheDatabaseRowsetGroups extends KDatabaseRowsetAbstract
{	
    protected function _initialize(KConfig $config)
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