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
 * Component Html View Class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_View
 */
class DefaultViewHtml extends KViewHtml
{
	/**
	 * Constructor
	 *
	 * @param	array An optional associative array of configuration settings.
	 */
	public function __construct(array $options = array())
	{
        parent::__construct($options);
        
        $template = KFactory::get('lib.koowa.application')->getTemplate();
        $override = JPATH_THEMES.DS.$template.DS.'html'.DS.'com_'.$this->_identifier->package.DS.$this->getName();
        $this->addTemplatePath($override);
	}
	
	/**
	 * Execute and echo's the views output
 	 *
	 * @return KViewDefault
	 */
	public function display()
	{
		//Get the model
		$model = $this->getModel();
		$name  = $this->getName();
		
		if(KInflector::isSingular($name))
		{
			// Disable the main menu
			if($this->_layout == 'form') {
				KRequest::set('get.hidemainmenu', 1);
			}
		}

		// Create the toolbar
		$toolbar = $this->getToolbar();

		// Render the toolbar
		$this->_document->setBuffer($toolbar->render(), 'modules', 'toolbar');

		// Render the title
		$this->_document->setBuffer($toolbar->renderTitle(), 'modules', 'title');

		// Display the layout
		parent::display();
	}

	/**
	 * Get the toolbar with the same identifier
	 *
	 * @return	KToolbarAbstract	A KToolbar object
	 */
	public function getToolbar(array $options = array())
	{
		$identifier			= clone $this->_identifier;
		$identifier->path	= array('toolbar');
		$identifier->name   = $this->getName();
		
		return KFactory::get($identifier, $options);
	}
}