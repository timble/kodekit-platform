<?php
/**
 * @version		$Id: html.php 1485 2012-02-10 12:32:02Z johanjanssens $
 * @package     Nooku_Components
 * @subpackage  Activities
 * @copyright	Copyright (C) 2010 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Activities Html View Class
 *
 * @author      Israel Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Activities
 */

class ComActivitiesViewActivitiesHtml extends ComDefaultViewHtml
{
	public function display()
	{
		if ($this->getLayout() == 'default')
		{
			$model = $this->getService($this->getModel()->getIdentifier());

			$this->assign('packages', $model
				->distinct(true)
				->column('package')
				->getList()
			);
		}

		return parent::display();
	}
}