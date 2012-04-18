<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Spam template helper class
 *
 * @author Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category Nooku
 * @package Nooku_Server
 * @subpackage Users
 */
class ComUsersTemplateHelperSpam extends KTemplateHelperDefault
{
	/**
	 * Inserts two hidden input fields for spamming detection based on timestamps.
	 * 
	 * @param array An optional configuration array.
	 * @throws KTemplateHelperException If the secret used for constructing the hash is missing.
	 * @return string The HTML output.
	 */
	public function timestamp($config = array())
	{
		
		$config = new KConfig($config);
		
		$secret = $this->getTemplate()
			->getView()->secret;
		
		if(empty($secret)) {
			throw new KTemplateHelperException('Secret missing.');
		}
		
		$timestamp = time();
		$timestamp_secret = sha1($timestamp . $secret);
		
		$html = '<input type="hidden" name="timestamp" value="' . $timestamp . '" />';
		$html .= '<input type="hidden" name="timestamp_secret" value="' . $timestamp_secret . '" />';
		return $html;
	}
}