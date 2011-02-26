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
		$layout = $this->getLayout();
		$id = $this->getModel()->getState()->id;
		
		if( $layout == 'form' ){
			$toolbarTitle = $id ? 'edit' : 'new';
		} else {
			$toolbarTitle = $layout;
		}
		
		KFactory::get('admin::com.sections.toolbar.section', array(
			'title' => "Section:[$toolbarTitle]",
			'icon'  => 'sections.png' )); 
       
		return parent::display();
   	}
}