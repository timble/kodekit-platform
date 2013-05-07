 <?php
/**
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

 use Nooku\Library;

/**
 * Weblink Controller Class
 *
 * @author    	Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @package     Nooku_Server
 * @subpackage  Weblinks
 */
class WeblinksControllerWeblink extends ApplicationControllerDefault
{
    public function getRequest()
	{
		$request = parent::getRequest();

		//Display only published items
		$request->query->published = 1;
		
		return $request;
	}
	
	public function _actionRead(Library\CommandContext $context)
	{
        $weblink = parent::_actionRead($context);

		// Redirect the user if the request doesn't include layout=form
		if ($context->request->getFormat() == 'html')
		{           
			if ($weblink->url) {
                $this->getObject('application')->redirect($weblink->url);
			}

			return true;
		}

		return $weblink;
	}
}