<?php
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Modules Html View Class
 *   
 * @author    	Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @package     Nooku_Server
 * @subpackage  Pages
 */
class PagesViewModulesHtml extends Library\ViewHtml
{
	public function render()
	{
		//Load language files for each module
	    if($this->getLayout() == 'list') 
		{
		    foreach($this->getModel()->getRowset() as $module)
		    {
                $path = Library\ClassLoader::getInstance()->getApplication($module->application);
                JFactory::getLanguage()->load($module->getIdentifier()->package, $module->name, $path );
		    }
		} 

        return parent::render();
	}
}