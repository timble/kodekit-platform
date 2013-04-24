<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Model State
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class ModelState extends Library\ModelState
{
	public function get($name, $default = null)
    {
    	$result = parent::get($name, $default);

        if ($name === 'container' && is_string($result))
        {
            $result = Library\ObjectManager::getInstance()->getObject('com:files.model.containers')->slug($result)->getRow();

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

        if (!empty($data['container']) && $data['container'] instanceof Library\DatabaseRowInterface) {
            $data['container'] = $data['container']->slug;
        }

        return $data;
    }
}