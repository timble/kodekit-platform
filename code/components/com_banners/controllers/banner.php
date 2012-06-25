<?php
/**
 * @version      $Id$
 * @category     Nooku
 * @package      Nooku_Server
 * @subpackage   Banners
 * @copyright    Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link         http://www.nooku.org
 */

/**
 * Banners Controller Class
 *
 * @author      Cristiano Cucco <http://nooku.assembla.com/profile/cristiano.cucco>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Banners    
 */
class ComBannersControllerBanner extends ComDefaultControllerDefault
{     
    public function _actionRead(KCommandContext $context)
	{
        $banner = parent::_actionRead($context);
        
		// Redirect the user if the banner has a url
		if ($banner->clickurl) 
		{
			// Increase hit counter
			if($banner->isHittable()) { 
			    $banner->hit(); 
			}
		    
		    JFactory::getApplication()->redirect($banner->clickurl);
			return true;
		}

		return $banner;
	}
}