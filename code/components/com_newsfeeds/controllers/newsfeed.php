 <?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Newsfeed Controller
 *
 * @author    	Babs Gšsgens <http://nooku.assembla.com/profile/babsgosgens>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 */
class ComNewsfeedsControllerNewsfeed extends ComDefaultControllerDefault
{
    public function getRequest()
	{
		//Display only enabled items
		$this->_request->enabled = 1;
		
		return parent::getRequest();
	}
}