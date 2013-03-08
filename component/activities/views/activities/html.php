<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

use Nooku\Framework;

/**
 * Activities Html View
 *
 * @author      Israel Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @package Nooku\Component\Activities
 */
class ComActivitiesViewActivitiesHtml extends ComDefaultViewHtml
{
	public function render()
	{
        if ($this->getLayout() == 'default')
		{
			$model = $this->getService($this->getModel()->getIdentifier());
            $this->packages = $model->distinct(true)->column('package')->getRowset();
		} 
		
		return parent::render();
	}
}