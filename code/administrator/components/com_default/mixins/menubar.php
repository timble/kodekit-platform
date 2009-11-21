<?php
/** 
 * @version		$Id: menu.php 257 2009-10-15 00:21:49Z johan $
 * @package		Koowa
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * Menubar mixin, can be used in all views to display to main menu
 */
class DefaultMixinMenubar extends KMixinAbstract
{
	/**
	 * Associatives array of view names
	 * 
	 * @var array
	 */
	protected $_views;
	
	/**
	 * Object constructor
	 *
	 * @param	array 	An optional associative array of configuration settings.
	 * Recognized key values include 'mixer' (this list is not meant to be comprehensive).
	 */
	public function __construct(array $options = array())
	{
		parent::__construct($options);
		
		$this->_views = $options['views'];
	}

	/**
     * Initializes the options for the object
     * 
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   array   Options
     * @return  array   Options
     */
    protected function _initialize(array $options)
    {
    	$options = parent::_initialize($options);
    	
    	$defaults = array(
            'views' =>  array(),
        );

        return array_merge($defaults, $options);
    }
	
	public function displayMenubar()
	{
		foreach($this->_views as $view => $title)
		{
			$active    = ($view == strtolower($this->_mixer->getName()) );
			$component = $this->_mixer->getIdentifier()->package;
			JSubMenuHelper::addEntry(JText::_($title), 'index.php?option=com_'.$component.'&view='.$view, $active );
		}
	}
}