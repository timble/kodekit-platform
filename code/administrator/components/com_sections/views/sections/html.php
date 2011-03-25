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
 * View HTML Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Sections  
 */
class ComSectionsViewSectionsHtml extends ComSectionsViewHtml
{
	public function display()
	{
   	    $this->getToolbar()
   	        ->setTitle('Section Manager')
			->append('divider')     
			->append(KFactory::tmp('admin::com.sections.toolbar.button.enable', array('text' => 'publish')))
			->append(KFactory::tmp('admin::com.sections.toolbar.button.disable', array('text' => 'unpublish')));
                                        
		return parent::display();
	}
	
}
