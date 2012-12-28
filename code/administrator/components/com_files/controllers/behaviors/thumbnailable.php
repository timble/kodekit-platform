<?php
/**
 * @version     $Id: executable.php 1671 2012-05-03 03:54:38Z johanjanssens $
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Attach thumbnais to the rows if they are available
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */
class ComFilesControllerBehaviorThumbnailable extends KControllerBehaviorAbstract
{
	protected function _afterControllerBrowse(KCommandContext $context)
	{
		if (!$context->request->query->get('thumbnails', 'cmd') || $this->getModel()->container->parameters->thumbnails !== true) {
			return;
		}

		$files = array();
		foreach ($context->result as $row) 
		{
			if ($row->getIdentifier()->name === 'file') {
				$files[] = $row->name;
			}
		}
		
		$folder = $context->request->query->get('folder', 'cmd');
		$thumbnails = $this->getService('com://admin/files.controller.thumbnail', array(
			'request' => array(
				'container' => $this->getModel()->container,
				'folder' => $folder,
				'filename' => $files,
				'limit' => 0,
				'offset' => 0
			)
		))->browse();
	
		foreach ($thumbnails as $thumbnail) 
		{
			if ($row = $context->result->find($thumbnail->filename)) {
				$row->thumbnail = $thumbnail->thumbnail;
			}
		}
		
		foreach ($context->result as $row) 
		{
			if (!$row->thumbnail) {
				$row->thumbnail = null;
			}
		}
	}
}
