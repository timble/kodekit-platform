<?php
/**
 * @version     $Id: sections.php 592 2011-03-16 00:30:35Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Cache Default Controller
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 */
 
class ComCacheControllerDefault extends ComDefaultControllerDefault 
{ 
    protected function _actionPurge(KCommandContext $context)
    {
        //Purge the cache
        if(JFactory::getCache('')->gc()) {
            $this->_redirect_message = JText::_( 'Expired items have been purged' );
        } else {
           $this->_redirect_message = JText::_('Error purging expired items');
        }
          
		$this->_redirect = KRequest::url();    
        return true;
    }
    
	public function getRequest()
	{
		$request = parent::getRequest();
		
	    //Force the site
	    $request->site = KFactory::get('joomla:application')->getSite();
	    
	    return $request;
	}
}