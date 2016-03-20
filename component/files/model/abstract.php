<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-files for the canonical source repository
 */

namespace Kodekit\Component\Files;

use Kodekit\Library;

/**
 * Abstract Model
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
 */
abstract class ModelAbstract extends Library\ModelAbstract
{
	public function __construct(Library\ObjectConfig $config)
	{
		parent::__construct($config);

        $this->getState()
            ->insert('limit'    , 'int')
            ->insert('offset'   , 'int')
            ->insert('sort'     , 'cmd')
            ->insert('direction', 'word', 'asc')
            ->insert('search'   , 'string')

			->insert('container', 'com:files.filter.container', null)
			->insert('folder'	, 'com:files.filter.path', '')
			->insert('name'		, 'com:files.filter.path', '', true)

			->insert('types'	, 'cmd', '')
			->insert('editor'   , 'string', '') // used in modal windows
			->insert('config'   , 'json', '')   // used to pass options to the JS application in HMVC
			;
	}
}