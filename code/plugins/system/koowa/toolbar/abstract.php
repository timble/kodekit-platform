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
 * @uses		KInflector
 * @uses		KMixinClass
 * @uses 		KFactory
 */
abstract class KToolbarAbstract extends KObject implements KToolbarInterface, KFactoryIdentifiable
{
	/**
	 * The toolbar title
	 *
	 * @var		string
	 */
	protected $_title = '';
	
	/**
	 * The toolbar icon
	 *
	 * @var		string
	 */
	protected $_icon = '';
	
	/**
	 * Buttons in the toolbar
	 *
	 * @var		array
	 */
	protected $_buttons = array();

	/**
	 * The object identifier
	 *
	 * @var object
	 */
	protected $_identifier = null;

	/**
	 * Constructor
	 *
	 * @param array	Options array
	 */
	public function __construct(array $options = array())
	{
        // Set the objects identifier
        $this->_identifier = $options['identifier'];

		// Initialize the options
        $options  = $this->_initialize($options);

        // Set the title
        $title = empty($options['title']) ? KInflector::humanize($this->getName()) : $options['title'];
        $this->setTitle($title);
        
        // Set the icon
        $this->setIcon($options['icon']);
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
            'title'	 	 => null,
        	'icon'		 => 'generic.png',
        	'identifier' => null
        );

        return array_merge($defaults, $options);
    }

	/**
	 * Get the identifier
	 *
	 * @return 	object A KFactoryIdentifier object
	 * @see 	KFactoryIdentifiable
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}

	/**
	 * Get the toolbar's name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->_identifier->name;
	}
	
	/**
	 * Set the toolbar's title
	 *
	 * @param 	string	Title
	 * @return 	KToolbarInterface
	 */
	public function setTitle($title)
	{
		$this->_title = $title;
		return $this;
	}
	
	/**
	 * Set the toolbar's icon
	 *
	 * @param 	string	Icon
	 * @return 	KToolbarInterface
	 */
	public function setIcon($icon)
	{
		$this->_icon = $icon;
		return $this;
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
	 * Reset the button array
	 *
	 * @return	this
	 */
	public function reset()
	{
		$this->_buttons = array();
		return $this;
	}

	/**
	 * Render the toolbar
	 *
	 * @throws KToolbarException When the button could not be found
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
			if(!($button instanceof KToolbarButtonInterface))
			{
				$app		= $this->_identifier->application;
				$package	= $this->_identifier->package;
				$button = KFactory::tmp($app.'::com.'.$package.'.toolbar.button.'.$button);
			}

			$button->setParent($this);
			$html[] = $button->render();
		}

		// End toolbar div
		$html[] = '</tr></table>';
		$html[] = '</div>';

		return implode(PHP_EOL, $html);
	}

	/**
	 * Set the toolbar's title and icon
	 *
	 * @param 	string	Title
	 * @param 	string	Icon
	 * @return 	KToolbarInterface
	 */
	public function renderTitle()
	{
		//strip the extension
		$icon  = preg_replace('#\.[^.]*$#', '', $this->_icon);
		$title = JText::_($this->_title);

		$html  = "<div class=\"header icon-48-$icon\">\n";
		$html .= "$title\n";
		$html .= "</div>\n";

		return $html;
	}
}