<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_View
 * @subpackage 	Ajax
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPL <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.koowa.org
 */

/**
 * Ajax View Class
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_View
 * @subpackage 	Ajax
 */
class KViewAjax extends KViewAbstract 
{ 
	public function __construct($options = array())
	{
		$options = $this->_initialize($options);
		
		// Add a rule to the template for form handling and secrity tokens
		KTemplate::addRules(array(KFactory::get('lib.koowa.template.filter.form')));
		
		// Set a base and media path for use by the view
		$this->assign('baseurl' , $options['base_url']);
		$this->assign('mediaurl', $options['media_url']);
		
		parent::__construct($options);
	}
}
