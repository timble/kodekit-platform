<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

/**
 * Application Event Subscriber
.*
 * @author		Johan Janssens <johan@nooku.org>
 * @package Nooku\Component\Debug
 */
class ComDebugEventSubscriberApplication extends KEventSubscriberAbstract
{
    public function __construct(KConfig $config)
	{
	    //Intercept the events for profiling
	    if($this->getService('application')->getCfg('debug'))
	    {
	        //Replace the event dispatcher
	        $this->getService()->setAlias('lib://nooku/event.dispatcher.default', 'com://admin/debug.event.profiler');
	          
	        //Add the database tracer
	        $this->getService('application.database')->addEventSubscriber('com://admin/debug.event.subscriber.database');
		}
		
		parent::__construct($config);
	}
}