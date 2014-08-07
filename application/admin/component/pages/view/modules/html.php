<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Modules Html View
 *   
 * @author  Stian Didriksen <http://github.com/stipsan>
 * @package Component\Pages
 */
class PagesViewModulesHtml extends Library\ViewHtml
{
    protected function _actionRender(Library\ViewContext $context)
	{
		//Load language files for each module
	    if($this->getLayout() == 'list') 
		{
		    foreach($this->getModel()->fetch() as $module) {
                $this->getObject('translator')->import($module->getIdentifier()->package);
		    }
		}

        //Load a unique list of module positions
        $positions = array();
        $modules = $this->getObject('com:pages.model.modules')->application('site')->fetch();
        foreach($modules as $module) {
            $positions[] = $module->position;
        }

        $this->positions = array_unique($positions);

        return parent::_actionRender($context);
	}
}