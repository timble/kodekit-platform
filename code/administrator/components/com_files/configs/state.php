<?php
/**
 * @version     $Id: file.php 1428 2012-01-20 17:14:12Z ercanozkaya $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

class ComFilesConfigState extends KConfigState
{
	public function get($name, $default = null)
    {
    	$result = parent::get($name, $default);
    	
        if ($name === 'container' && is_string($result)) {
            $result = KService::get('com://admin/files.model.containers')->slug($result)->getItem();

	        if (!is_object($result) || $result->isNew()) {
	            throw new KModelException('Invalid container');
	        }
	        
	        $this->_data['container']->value = $result;
        }
        
        return $result;
  	}
}