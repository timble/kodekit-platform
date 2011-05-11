<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Module Controller Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 */

class ComModulesControllerModule extends ComDefaultControllerDefault
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->registerCallback('after.edit', array($this, 'purgeCache'));
	}
	
	public function purgeCache(KCommandContext $context)
	{
		$cache = JFactory::getCache();
		foreach($context->result as $row)
		{
			// clean cache for all 3 front-end user groups (guest, reg, special)
			$cache->remove($row->id . '0', $row->module);
			$cache->remove($row->id . '1', $row->module);
			$cache->remove($row->id . '2', $row->module);
		}
		
		// clean content cache because of loadposition plugin
		$cache->clean( 'com_content' );
	}
}