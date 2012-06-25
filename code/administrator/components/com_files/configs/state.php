<?php
/**
 * @version     $Id: file.php 1428 2012-01-20 17:14:12Z ercanozkaya $
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

class ComFilesConfigState extends KConfigState
{
	/**
	 * Needed to make sure form filter does not add config to the form action
	 */
	public function getData($unique = false)
	{
		$data = parent::getData($unique);
		
		unset($data['config']);
		
		return $data;
	}
	
	public function get($name, $default = null)
    {
    	$result = parent::get($name, $default);

        if ($name === 'container' && is_string($result))
        {
            $result = KService::get('com://admin/files.model.containers')->slug($result)->getItem();

	        if (!is_object($result) || $result->isNew()) {
	            throw new KModelException('Invalid container');
	        }

	        $this->_data['container']->value = $result;
        }

        return $result;
  	}
  	
    public function toArray($values = true)
    {
    	$array = parent::toArray($values);
    	if (!empty($array['container']) && $array['container'] instanceof KDatabaseRowInterface) {
    		$array['container'] = $array['container']->slug;
    	}

        return $array;
    }
}