<?php
/**
 * @version		$Id: dispatcher.php 693 2011-04-26 18:46:51Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Versions
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
 * @subpackage 	Versions
 */

class plgKoowaDebug extends PlgKoowaDefault
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
	
	public function onDatabaseAfterSelect(KCommandContext $context)
	{
		$this->queries[] = $context->query;
	}
	
	public function onDatabaseAfterUpdate(KCommandContext $context)
	{
		$this->queries[] = $context->query;
	}
		
	public function onDatabaseAfterInsert(KCommandContext $context)
	{
		$this->queries[] = $context->query;
	}
		
	public function onDatabaseAfterDelete(KCommandContext $context)
	{
		$this->queries[] = $context->query;
	}
	
	public function onDatabaseAfterShow(KCommandContext $context)
	{
		$this->queries[] = $context->query;
	}
}