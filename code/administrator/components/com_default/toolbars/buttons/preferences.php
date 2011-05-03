<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Preferences Toolbar Button
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultToolbarButtonPreferences extends KToolbarButtonGet
{    
 	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $name = $this->_identifier->name;

        $config->append(array(
            'width'   => '640',
            'height'  => '480',
        ));

        parent::_initialize($config);
    }
	
	/**
	 * Generates the link
	 *
	 * @return	string	Link for button
	 */
	public function getLink()
	{
        return 'index.php?option=com_config&controller=component&component=com_'.$this->_identifier->package;
	}

	/**
	 * Renders the button
	 *
	 * Must include @helper('behavior.modal') in view, to load the modal behavior
	 *
	 * @return	string	Html for button
	 */
	public function render()
	{
		$text	= JText::_($this->_options->text);

		$link   = $this->getLink();
		$href   = !empty($link) ? 'href="'.JRoute::_($link).'"' : '';

		$onclick =  $this->getOnClick();
		$onclick = !empty($onclick) ? 'onclick="'. $onclick.'"' : '';

		$html 	= array ();
		$html[]	= '<td class="button" id="'.$this->getId().'">';
		$html[]	= '<a '.$href.' '.$onclick.' class="toolbar modal" rel="{handler: \'iframe\', size: {x: '.$this->_option->width.', y: '.$this->_options->height.'}}">';

		$html[]	= '<span class="'.$this->getClass().'" title="'.$text.'">';
		$html[]	= '</span>';
		$html[]	= $text;
		$html[]	= '</a>';
		$html[]	= '</td>';

		return implode(PHP_EOL, $html);
	}
}