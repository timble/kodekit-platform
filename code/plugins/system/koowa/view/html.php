<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_View
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
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
class KViewHtml extends KViewTemplate
{
	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		// Add a rule to the template for form handling and secrity tokens
		KTemplate::addRules(array(KFactory::get('lib.koowa.template.filter.form')));

		// Set base and media urls for use by the view
		$this->assign('baseurl' , $config->base_url)
			 ->assign('mediaurl', $config->media_url);

		parent::__construct($config);
	}
	
	/**
	 * Renders and echo's the views output
 	 *
	 * @return KViewHtml
	 */
	public function display()
	{
		$model = KFactory::get($this->getModel());
		
		//Auto-assign the state to the view
		$this->assign('state', $model->getState());
		
		//Get the view name
		$name  = $this->getName();
		
		//Assign the data of the model to the view
		if(KInflector::isPlural($name))
		{
			$this->assign($name, 	$model->getList())
				 ->assign('total',	$model->getTotal());
		}
		else
		{
			$this->assign($name, $model->getItem());
		}
		
		//Load the template
		$template = $this->loadTemplate();
		
		//Render the scripts
		if(KRequest::type() == 'AJAX')
		{
			foreach ($this->_document->_scripts as $source => $type) {
				echo '<script type="'.$type.'" src="'.$source.'"></script>'."\n";
			}
		}
	
		//Render the template
		echo $template;
		
		return $this;
	}
}