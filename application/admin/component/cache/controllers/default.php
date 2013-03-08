<?php
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Cache Default Controller
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 */
 
class ComCacheControllerDefault extends ComDefaultControllerModel
{ 
    protected function _actionPurge(Framework\CommandContext $context)
    {
        //Purge the cache
        if(JFactory::getCache('')->gc()) {
            $message = JText::_( 'Expired items have been purged' );
        } else {
           $message = JText::_('Error purging expired items');
        }

        $context->response->setRedirect($context->request-getUrl(), $message);
        return true;
    }
    
	public function getRequest()
	{
		$request = parent::getRequest();
		
	    //Force the site
	    //$request->site = $this->getService('application')->getSite();
	    
	    return $request;
	}
}