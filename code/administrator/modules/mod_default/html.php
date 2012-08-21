<?php
/**
 * @version     $Id$
 * @package     Nooku_Modules
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Module View
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Modules
 * @subpackage  Default
 */
class ModDefaultHtml extends KViewHtml
{
    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'media_url'  => KRequest::root() . '/media',
            'data'	     => array()
        ));

        parent::_initialize($config);
    }

	/**
	 * Get the name
	 *
	 * @return 	string 	The name of the object
	 */
	public function getName()
	{
		return $this->getIdentifier()->package;
	}

	/**
     * Renders and echo's the views output
     *
     * @return ModDefaultHtml
     */
    public function display()
    {
        //Load the language files.
        JFactory::getLanguage()->load($this->module->type);

        //Dynamically attach the chrome filter
        if(!empty($this->module->chrome)) {
            $this->getTemplate()->attachFilter('chrome', array('styles' => $this->module->chrome));
        }

		if(empty($this->module->content))
		{
            $identifier = clone $this->getIdentifier();
            $identifier->name = $this->getLayout();

            $this->output = $this->getTemplate()
                ->loadIdentifier($identifier, $this->_data)
                ->render();
		}
		else
		{
		     $this->output = $this->getTemplate()
                ->loadString($this->module->content, $this->_data, false)
                ->render();
		}

        return $this->output;
    }
}