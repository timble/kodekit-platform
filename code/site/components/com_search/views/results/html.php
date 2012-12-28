<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Search Html View Class
 *
 * @author    	Arunas Mazeika <http://nooku.assembla.com/profile/amazeika>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 */
class ComSearchViewResultsHtml extends ComDefaultViewHtml
{
	/**
	 * Return the views output
	 * 
	 * This function will auto assign the model data to the view if the auto_assign
	 * property is set to TRUE.
	 *
	 * @return string     The output of the view
	 */
	public function display()
	{
        $model = $this->getModel();

		$params = $this->getService('application')->getParams();

        $this->assign('params'  , $params);
        $this->assign('results' , $model->getRowset());
        $this->assign('total'   , $model->getTotal());

        return parent::display();
	}
}