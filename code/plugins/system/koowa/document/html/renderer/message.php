<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Document
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * System message renderer
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package		Koowa_Document
 * @subpackage	Html
 * @uses		KFactory
 */
class KDocumentHtmlRendererMessage extends KDocumentRenderer
{
	/**
	 * Renders the error stack and returns the results as a string
	 *
	 * @param string 	$name		(unused)
	 * @param array 	$params		Associative array of values
	 * @return string	The output of the script
	 */
	public function render($name, array $params = array (), $contents = null)
	{
		// Initialize variables
		$contents	= null;
		$lists		= null;

		// Get the message queue
		$messages = KFactory::get('lib.joomla.application')->getMessageQueue(); 

		// Build the sorted message list
		if (is_array($messages) && count($messages)) 
		{
			foreach ($messages as $msg)
			{
				if (isset($msg['type']) && isset($msg['message'])) {
					$lists[$msg['type']][] = $msg['message'];
				}
			}
		}

		$contents .= "\n<dl id=\"system-message\" style=\"margin: 0px;\">";
		// If messages exist render them
		if (is_array($lists))
		{
			// Build the return string
			foreach ($lists as $type => $msgs)
			{
				if (count($msgs)) {
					$contents .= "\n<dt class=\"".strtolower($type)."\">".JText::_( $type )."</dt>";
					$contents .= "\n<dd class=\"".strtolower($type)." message fade\">";
					$contents .= "\n\t<ul>";
					foreach ($msgs as $msg) {
						$contents .="\n\t\t<li>".$msg."</li>";
					}
					$contents .= "\n\t</ul>";
					$contents .= "\n</dd>";
				}
			}
		}
		$contents .= "\n</dl>";
		return $contents;
	}
}