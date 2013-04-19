<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

use Nooku\Library;

/**
 * Activities Html View
 *
 * @author      Israel Canasa <http://nooku.assembla.com/profile/israelcanasa>
 */
class ActivitiesViewActivitiesHtml extends Library\ViewHtml
{
	public function render()
	{
        if ($this->getLayout() == 'default')
		{
			$model = $this->getObject($this->getModel()->getIdentifier());
            $this->packages = $model->distinct(true)->column('package')->getRowset();
		} 
		
		return parent::render();
	}
}