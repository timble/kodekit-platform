<?php
/**
 * @version     $Id: koowa.php 2775 2011-01-01 17:02:39Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Plugins
 * @subpackage  System
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Koowa Debug plugin
.*
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Nooku
 * @package     Nooku_Plugins
 * @subpackage  System
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgSystemDebug extends JPlugin
{
    public function __construct($subject, $config = array())
	{
	    //Intercept the events
	    if(KDEBUG) {
	        KFactory::set('lib.koowa.event.dispatcher', KFactory::tmp('admin::com.debug.event.dispatcher'));
		}
		
		parent::__construct($subject, $config);
	}
    
    public function onAfterRender()
	{
		//Render the debug information
	    if(KDEBUG) 
		{
		    if(JFactory::getDocument()->getType() == 'html') 
		    {
		       $html = KFactory::get('admin::com.debug.controller.debug')->display();
		    
		        $body = JResponse::getBody();
		        $body = str_replace('</body>', $html.'</body>', $body);
		        JResponse::setBody($body);
		    }
		}
	}
}