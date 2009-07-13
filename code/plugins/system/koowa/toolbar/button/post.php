<?php
/**
* @version      $Id$
* @category		Koowa
* @package		Koowa_Toolbar
* @subpackage	Button
* @copyright    Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPL <http://www.gnu.org/licenses/gpl.html>
*/

/**
 * POST button class for a toolbar
 * 
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_Toolbar
 * @subpackage	Button
 */
abstract class KToolbarButtonPost extends KToolbarButtonAbstract
{
	protected $_fields = array();
	
	public function __construct(array $options = array())
	{
		parent::__construct($options);
		$this->setMethod('post');
		
		$script = Koowa::getURL('js').'koowa.js';
		KFactory::get('lib.joomla.document')->addScript($script);
	}
	
	public function getOnClick()
	{
		$js = '';
		foreach($this->_fields as $name => $value) {
			$js .= "Koowa.Form.addField('$name', '$value');";
		}
		$js .= "Koowa.Form.submit('{$this->_method}');";
		return $js;
	}
	
	public function setField($name, $value)
	{
		$this->_fields[$name] = $value;
		return $this;
	}
}