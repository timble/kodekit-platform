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
 * Categories HTML View Class
 *   
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Categories
 */
class ComCategoriesViewCategoryHtml extends ComCategoriesViewHtml
{
   	public function display()
   	{
		$title = $this->getModel()->get('id') ?  'Edit Category' : 'New Category';
   	    $this->getToolbar()->setTitle($title);
   	 
		return parent::display();
   	}
}
