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
 * Sections Html View Class
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
   	        ->setTitle('Sections')
			->append('divider')     
			->append('enable', array('label' => 'publish')))
			->append('disable', array('label' => 'unpublish')));
                                        
		return parent::display();
	}
}