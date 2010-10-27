<?php
/**
 * @version     $Id: default.php 2576 2010-09-11 12:39:05Z johanjanssens $
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Default Template
.*
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Default
 */
class ComDefaultTemplateFilterScript extends KTemplateFilterScript
{	
   	/**
	 * Render script information
	 * 
	 * @param string	The script information
	 * @param boolean	True, if the script information is a URL.
	 * @param array		Associative array of attributes
	 * @return string 	
	 */
	protected function _renderScript($script, $link, $attribs = array())
	{	
		if(KRequest::type() == 'AJAX') {
			return parent::_render($script, $link, $attribs);
		}
		
		$document = KFactory::get('lib.joomla.document');
		
		if($link) {
			$document->addScript($script, 'text/javascript');
		} else {
			$document->addScriptDeclaration($script);
		}
	}
}