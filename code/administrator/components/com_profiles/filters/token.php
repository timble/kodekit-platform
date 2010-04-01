<?php
/**
* @version      $Id$
* @category		Koowa
* @package      Koowa_Template
* @subpackage	Filter
* @copyright    Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.koowa.org
*/

/**
 * Template filter for tokens such as @template, @text, @helper, @route etc
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Template
 * @subpackage	Filter
 */
class ComProfilesFilterToken extends KTemplateFilterToken
{
	/**
	 * Tags => replacement
	 *
	 * @var array
	 */
	protected $_tags = array(
		'@js('			=> "KTemplate::loadHelper('admin::com.profiles.helper.media.js', ",
	);
}


			