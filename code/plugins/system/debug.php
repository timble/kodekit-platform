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
	    //Intercept the events for profiling
	    if(KDEBUG) 
	    {
	        //Create the event profiler
	        $profiler = KFactory::get('com://admin/debug.profiler.events');
	        
	        //Replace the event dispatcher
	        KFactory::set('koowa:event.dispatcher', $profiler);
		}
		
		parent::__construct($subject, $config);
	}
}