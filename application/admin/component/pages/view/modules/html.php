<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Modules Html View
 *   
 * @author  Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @package Component\Pages
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