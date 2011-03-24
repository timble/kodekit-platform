<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * View HTML Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories
 */
class ComCategoriesViewCategoriesHtml extends ComCategoriesViewHtml
{
	public function display()
	{
		$this->getToolbar()
		    ->setTitle('Category Manager : ['.$this->getModel()->get('section').']')
			->append('divider')     
			->append(KFactory::tmp('admin::com.categories.toolbar.button.enable', array('text' => 'publish')))
			->append(KFactory::tmp('admin::com.categories.toolbar.button.disable', array('text' => 'unpublish')))
			->append('divider')
			->append('edit');
                                        
		return parent::display();
	}
}
