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
 * Activities Html View
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Activities
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