 <?php
/**
 * @version		$Id$
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Weblink Controller Class
 *
 * @author    	Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @package     Nooku_Server
 * @subpackage  Weblinks
 */
class ComWeblinksControllerWeblink extends ComDefaultControllerDefault
{
    public function getRequest()
	{
		//Display only published items
		$this->_request->published = 1;
		
		return parent::getRequest();
	}
	
	public function _actionRead(KCommandContext $context)
	{
        $weblink = parent::_actionRead($context);

		// Redirect the user if the request doesn't include layout=form
		if ($this->_request->format == 'html')
		{           
			if ($weblink->url) {
                $context->response->setRedirect($weblink->url);
			}

			return true;
		}

		return $weblink;
	}
}