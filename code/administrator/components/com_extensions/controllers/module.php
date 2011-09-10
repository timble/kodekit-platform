<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions
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
 * @subpackage  Extensions
 */

class ComExtensionsControllerModule extends ComDefaultControllerDefault
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
			// Clean cache for all 3 front-end user groups (guest, reg, special)
			$cache->remove($row->id . '0', $row->module);
			$cache->remove($row->id . '1', $row->module);
			$cache->remove($row->id . '2', $row->module);
		}
		
		// Clean content cache because of loadposition plugin
		$cache->clean( 'com_content' );
	}
}