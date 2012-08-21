<?php
/**
 * @version		$Id: html.php 3532 2012-04-02 12:00:49Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Categories Html View
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Weblinks
 */
class ComArticlesViewCategoriesHtml extends ComDefaultViewHtml
{
	public function display()
	{
		$params = $this->getService('application')->getParams();
		$this->assign('params', $params);

		return parent::display();
	}
}