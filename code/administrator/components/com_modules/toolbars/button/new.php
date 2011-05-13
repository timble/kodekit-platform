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
 * Next Toolbar Button Class
 * 
 * @author    	Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 */

class ComModulesToolbarButtonNew extends ComDefaultToolbarButtonNew
{
    public function getOnClick()
    {
        //@TODO hacky, suggest patch to toolbar that allows setting the rel attribute
    	return '" rel="{handler:\'url\',ajaxOptions:{method:\'get\'}}';
    }

	public function getLink()
	{
	    $model = KFactory::get('admin::com.modules.model.modules');
		return 'index.php?option=com_modules&view=modules&layout=list&new=1&tmpl=component&application='.$model->getState()->application;
	}
}