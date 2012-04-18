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
 * Captcha template helper class
 *
 * @author      Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersTemplateHelperCaptcha extends KTemplateHelperDefault
{
	
	public function render($config = array())
	{
		
		$config = new KConfig($config);
		
		// Include the reCaptcha lib.
		require_once (JPATH_LIBRARIES . '/recaptcha/recaptchalib.php');

		$output = recaptcha_get_html($config->public_key);
		// Avoid the template filter from parsing script tags
		$output = str_replace('<script','<script data-inline', $output);
		
		return $output;
	}

}