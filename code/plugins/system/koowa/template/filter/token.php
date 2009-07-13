<?php
/**
* @version      $Id: helpers.php 506 2008-10-04 14:40:02Z mathias $
* @category		Koowa
* @package      Koowa_Template
* @subpackage	Filter
* @copyright    Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPL <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.koowa.org
*/

/**
 * Template filter for tokens such as @template, @text, @helper, @route
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Template
 * @subpackage	Filter
 */
class KTemplateFilterToken extends KObject implements KTemplateFilterInterface
{
	/**
	 * Tags => replacement
	 *
	 * @var array
	 */
	protected $_tags = array(
		'@template('	=> '$this->loadTemplate(',
		'@text('	 	=> 'JText::_(',
		'@helper('   	=> 'KTemplate::loadHelper(',
		'@route('    	=> '$this->createRoute(',
		'@script('    	=> "KTemplate::loadHelper('script',",
		'@style('    	=> "KTemplate::loadHelper('stylesheet',",
	);
	
	/**
	 * Convert the tags
	 *
	 * @param string $text
	 */
	public function parse(&$text) 
	{
		$text = str_replace(
			array_keys($this->_tags), 
			array_values($this->_tags), 
			$text);
	}
}


			