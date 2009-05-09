<?php
/**
* @version      $Id$
* @category		Koowa
* @package		Koowa_Toolbar
* @copyright    Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
*/

/**
 * Abstract Toolbar class
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_Toolbar
 */
abstract class KToolbarAbstract extends KObject implements KToolbarInterface
{
	protected $options = array();
	
	/**
	 * Buttons in the toolbar
	 *
	 * @var		array
	 */
	protected $_buttons = array();

	/**
	 * Constructor
	 * 
	 * @param array	Options array
	 */
	public function __construct(array $options = array())
	{
        // Initialize the options
        $this->_options  = $this->_initialize($options);
        
        // Mixin the KClass
        $this->mixin(new KMixinClass($this, 'Toolbar'));

        // Assign the classname with values from the config
        $this->setClassName($this->_options['name']);
	}
	
	/**
	 * Get the toolbar's name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->getClassName('suffix');
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
        $defaults = array(
            'name'          => array(
                        'prefix'    => 'k',
                        'base'      => 'toolbar',
                        'suffix'    => 'default'
                        )     
        );

        return array_merge($defaults, $options);
    }    

	/**
	 * Append a button
	 *
	 * @param	KToolbarButtonInterface|string	Button
	 * @return	this
	 */
	public function append($button)
	{
		array_push($this->_buttons, $button);
		return $this;
	}

	/**
	 * Prepend a button
	 *
	 * @param	KToolbarButtonInterface	Button
	 * @return	this
	 */
	public function prepend($button)
	{
		array_unshift($this->_buttons, $button);
		return $this;
	}

	/**
	 * Render the toolbar
	 * 
	 * @return	string	HTML
	 */
	public function render()
	{
		$id		= 'toolbar-'.$this->getName();
		$html = array ();

		// Start toolbar div
		$html[] = '<div class="toolbar" id="'.$id.'">';
		$html[] = '<table class="toolbar"><tr>';

		// Render each button in the toolbar
		foreach ($this->_buttons as $button) 
		{
			if(!($button instanceof KToolbarButtonInterface)) {
				$button = KFactory::tmp($button);
			}
			$button->setParent($this);
			$html[] = $button->render();
		}

		// End toolbar div
		$html[] = '</tr></table>';
		$html[] = '</div>';

		return implode(PHP_EOL, $html);
	}
}