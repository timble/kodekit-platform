<?php
/**
 * @version   	$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright  	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license   	GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Toolbar
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultToolbarDefault extends KToolbarDefault
{
	/**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
       
        $name  = $this->_identifier->name;
        
        if(KInflector::isPlural($name))
        {        
            $this->append('new')
                 ->append('delete');    
        }
        else
        {
            $this->append('save')
                 ->append('apply')
                 ->append('cancel');
        }
    }
    
    public function render()
	{
		$id		= 'toolbar-'.$this->getName();
		$html = array ();

		// Start toolbar div
		if (version_compare(JVERSION,'1.6.0','ge')) {
		    $html[] = '<div class="toolbar-list" id="'.$id.'">';
		} else {
			$html[] = '<div class="toolbar" id="'.$id.'">';
		}

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

	public function renderTitle()
	{
		//strip the extension
		$icon  = preg_replace('#\.[^.]*$#', '', $this->_icon);
		$title = JText::_($this->_title);

		$html  = '<div class="header pagetitle icon-48-'.$icon.'">';
		if (version_compare(JVERSION,'1.6.0','ge')) {
			$html .= '<h2>'.$title.'</h2>';
		} else {
		    $html .= $title;
		}
		
		$html .= '</div>';

		return $html;
	}
}