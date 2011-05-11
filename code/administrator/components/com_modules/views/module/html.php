<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Module HTML View Class
 *   
 * @author    	Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 */

class ComModulesViewModuleHtml extends ComDefaultViewHtml
{
	public function display()
	{
		if($this->getLayout() == 'form') {
			$this->positions = $this->getModel()->getPositions();
		} 
		
		if($this->getLayout() == 'list') 
		{
			$this->getToolbar()
			    ->reset()
				->append('next')
				->append('cancel');

			//The model getModules method is different from getList in that its domain is the filesystem not the database
			$this->modules = $this->getModel()->getModules();
		}
		
		return parent::display();
	}
}