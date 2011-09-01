<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * File Controller Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */
class ComFilesControllerFile extends ComFilesControllerNode
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->registerCallback('before.add', array($this, 'beforeAdd'));
	}

	public function beforeAdd(KCommandContext $context)
	{
		if (!$context->data->file)
		{
			$context->data->file = KRequest::get('files.file.tmp_name', 'raw');
			$context->data->path = KRequest::get('files.file.name', 'koowa.filter.filename');
		}
	}
}
