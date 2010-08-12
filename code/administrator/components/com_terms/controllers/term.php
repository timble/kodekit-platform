<?php
/**
 * @version		$Id$
 * @package		Tags
 * @copyright	Copyright (C) 2009 - 2010 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComTermsControllerTerm extends ComDefaultControllerDefault
{	
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		//Prevent state from being saved
		$this->unregisterCallbackAfter('browse'  , array($this, 'saveRequest'));
	}
}