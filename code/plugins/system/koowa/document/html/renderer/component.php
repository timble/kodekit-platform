<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Document
 * @subpackage 	Html
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPL <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.koowa.org
 */

/**
 * Component renderer
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_Document
 * @subpackage	Html
 */
class KDocumentHtmlRendererComponent extends KDocumentRenderer
{
	/**
	 * Renders a component script and returns the results as a string
	 *
	 * @param string 	$component	The name of the component to render
	 * @param array 	$params	Associative array of values
	 * @return string	The output of the script
	 */
	public function render( $component, array $params = array(), $content = null )
	{
		return $content;
	}
}
