<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Sections
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Sections HTML View Class
 *   
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Sections
 */
class ComSectionsViewSectionHtml extends ComSectionsViewHtml
{
   	public function display()
   	{
		$id = $this->getModel()->getState()->id;
		
		KFactory::get('admin::com.sections.toolbar.section', array(
			'title' => $id ? 'Edit Section' : 'New Section',
			'icon'  => 'sections.png' )); 
       
		return parent::display();
   	}
}