<?php
/**
 * @version		$Id: dispatcher.php 693 2011-04-26 18:46:51Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Plugins
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Debug plugin
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Plugins
 */

class PlgKoowaDebug extends PlgKoowaDefault
{
	public $queries = array();
	
	public function update(KConfig $args)
	{		
		if(KDEBUG) 
		{
			KFactory::get('lib.joomla.profiler')->mark( $args->event );
			return parent::update($args);
		}
	}
	
	public function onDatabaseAfterSelect(KEvent $event)
	{
		$this->queries[] = $event->query;
	}
	
	public function onDatabaseAfterUpdate(KEvent $event)
	{
		$this->queries[] = $event->query;
	}
		
	public function onDatabaseAfterInsert(KEvent $event)
	{
		$this->queries[] = $event->query;
	}
		
	public function onDatabaseAfterDelete(KEvent $event)
	{
		$this->queries[] = $event->query;
	}
	
	public function onDatabaseAfterShow(KEvent $event)
	{
		$this->queries[] = $event->query;
	}
}