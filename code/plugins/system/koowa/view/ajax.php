<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_View
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Ajax View Class
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_View
 */
class KViewAjax extends KViewAbstract
{
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
	 * @return KViewAjax
	 */
	public function display()
	{
		//Auto-assign the state to the view
		$this->assign('state', KFactory::get($this->getModel())->getState());
		
		//Load the template
		$template = $this->loadTemplate();
		
		//Render the scripts
		foreach ($this->_document->_scripts as $source => $type) {
			echo '<script type="'.$type.'" src="'.$source.'"></script>'."\n";
		}
	
		//Render the template
		echo $template;
		
		return $this;
	}
}
