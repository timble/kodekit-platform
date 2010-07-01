<?php
/**
 * @version		$Id$
 * @package		Terms
 * @copyright	Copyright (C) 2009 - 2010 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComTermsViewHtml extends ComDefaultViewHtml
{
	public function __construct(KConfig $config)
	{
        $config->views = array(
			'terms' 		=> JText::_('Terms'),
		);
		
		parent::__construct($config);
	}
}