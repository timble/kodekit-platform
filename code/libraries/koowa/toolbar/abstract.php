<?php
/**
* @version      $Id$
* @category		Koowa
* @package		Koowa_Toolbar
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
*/

/**
 * Abstract Toolbar class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Toolbar
 * @uses        KInflector
 * @uses        KMixinClass
 * @uses        KFactory
 */
abstract class KToolbarAbstract extends KObject implements KToolbarInterface, KObjectIdentifiable
{
    /**
     * The toolbar title
     *
     * @var     string
     */
    protected $_title = '';
    
    /**
     * The toolbar icon
     *
     * @var     string
     */
    protected $_icon = '';
    
    /**
     * Buttons in the toolbar
     *
     * @var     array
     */
    protected $_buttons = array();

    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config = null)
    {
        //If no config is passed create it
        if(!isset($config)) $config = new KConfig();
        
        parent::__construct($config);

        // Set the title
        $title = empty($config->title) ? KInflector::humanize($this->getName()) : $config->title;
        $this->setTitle($title);
        
        // Set the icon
        $this->setIcon($config->icon);
    }

    /**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'title'      => null,
            'icon'       => 'generic.png',
        ));
        
        parent::_initialize($config);
    }
    
    /**
     * Get the object identifier
     * 
     * @return  KIdentifier 
     * @see     KObjectIdentifiable
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
     * @param   string  Title
     * @return  KToolbarInterface
     */
    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }
    
    /**
     * Set the toolbar's icon
     *
     * @param   string  Icon
     * @return  KToolbarInterface
     */
    public function setIcon($icon)
    {
        $this->_icon = $icon;
        return $this;
    }

    /**
     * Append a button
     *
     * @param   KToolbarButtonInterface|string  Button
     * @return  this
     */
    public function append($button)
    {
        array_push($this->_buttons, $button);
        return $this;
    }

    /**
     * Prepend a button
     *
     * @param   KToolbarButtonInterface Button
     * @return  this
     */
    public function prepend($button)
    {
        array_unshift($this->_buttons, $button);
        return $this;
    }
    
    /**
     * Reset the button array
     *
     * @return  this
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
     * @return  string  HTML
     */
    public function render()
    {
        $id     = 'toolbar-'.$this->getName();
        $html = array ();

        // Start toolbar div
        $html[] = '<div class="toolbar" id="'.$id.'">';
        $html[] = '<table class="toolbar"><tr>';

        // Render each button in the toolbar
        foreach ($this->_buttons as $button)
        {
            if(!($button instanceof KToolbarButtonInterface))
            {
                $app        = $this->_identifier->application;
                $package    = $this->_identifier->package;
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
     * @param   string  Title
     * @param   string  Icon
     * @return  KToolbarInterface
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