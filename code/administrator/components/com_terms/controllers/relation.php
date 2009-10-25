<?php
/**
 * @version		$Id$
 * @package		Tags
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class TermsControllerRelation extends KControllerBread
{	
	public function __construct(array $options = array())
	{
		parent::__construct($options);
		     
		//Prevent state from being saved
		$this->unregisterFilterAfter('browse'  , 'filterSaveState');
	}
}