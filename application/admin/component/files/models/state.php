<?php
/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

class ComFilesModelState extends Framework\ModelState
{
	public function get($name, $default = null)
    {
    	$result = parent::get($name, $default);

        if ($name === 'container' && is_string($result))
        {
            $result =  Framework\ServiceManager::get('com://admin/files.model.containers')->slug($result)->getRow();

	        if (!is_object($result) || $result->isNew()) {
	            throw new \UnexpectedValueException('Invalid container');
	        }

	        $this->_data['container']->value = $result;
        }

        return $result;
  	}

    /**
     * Needed to make sure form filter does not add config to the form action
     */
    public function toArray($unique = false)
    {
        $data = parent::toArray($unique);
        unset($data['config']);

        if (!empty($data['container']) && $data['container'] instanceof Framework\DatabaseRowInterface) {
            $data['container'] = $data['container']->slug;
        }

        return $data;
    }
}