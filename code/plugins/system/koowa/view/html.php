<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_View
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * View HTML Class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_View
 */
class KViewHtml extends KViewAbstract
{
	/**
	 * Constructor
	 *
	 * @param	array An optional associative array of configuration settings.
	 */
	public function __construct($options = array())
	{
		$options = $this->_initialize($options);

		// Add a rule to the template for form handling and secrity tokens
		KTemplate::addRules(array(KFactory::get('lib.koowa.template.filter.form')));

		// Set base and media urls for use by the view
		$this->assign('baseurl' , $options['base_url'])
			 ->assign('mediaurl', $options['media_url']);

		parent::__construct($options);
	}
	
	/**
	 * Renders and echo's the views output
 	 *
	 * @return KViewHtml
	 */
	public function display()
	{
		//Auto-assign the state to the view
		$this->assign('state', KFactory::get($this->getModel())->getState());
		
		//Render the template
		echo $this->loadTemplate();
		
		return $this;
	}
}