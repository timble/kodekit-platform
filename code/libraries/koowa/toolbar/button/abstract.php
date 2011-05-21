<?php
/**
* @version      $Id$
* @category		Koowa
* @package		Koowa_Toolbar
* @subpackage	Button
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
*/

/**
 * Base button class for a toolbar
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Toolbar
 * @subpackage  Button
 * @uses        KInflector
 */
abstract class KToolbarButtonAbstract extends KObject implements KToolbarButtonInterface,  KObjectIdentifiable
{
    /**
     * Method used to submit the form
     *
     * @var string  [GET|POST|DELETE|PUT]
     */
    protected $_method;

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

        if($config->parent) {
            $this->setParent($config->parent);
        }

        $this->setMethod($config->method);

        $this->_options = $config;
    }

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
            'parent'     => null,
            'icon'       => 'icon-32-'.$name,
            'id'         => $name,
            'text'       => ucfirst($name),
            'method'     => 'get',
            'attribs'    => array(
                'class'    => 'toolbar'
            )
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
     * Set the parent toolbar
     *
     * @param   KToolbarInterface   Toolbar
     * @return  this
     */
    public function setParent(KToolbarInterface $toolbar)
    {
        $this->_parent = $toolbar;
        return $this;
    }

    /**
     * Get the parent toolbar
     *
     * @return  KToolbarInterface   Toolbar
     */
    public function getParent()
    {
        return $this->_parent;
    }

    /**
     * Set the method used to submit the form
     *
     * @param   string  [get|post|delete]
     * @return  this
     */
    public function setMethod($method)
    {
        $this->_method = strtolower($method);
        return $this;
    }

    /**
     * Get the element name
     *
     * @return  string  Button name
     */
    public function getName()
    {
        return $this->_identifier->name;
    }

    public function getLink()
    {
        return '';
    }

    public function getOnClick()
    {
        return '';
    }

    public function getId()
    {
        return 'toolbar-'.$this->getParent()->getName().'-'.$this->_options->id;
    }

    public function getClass()
    {
        return $this->_options->icon;
    }
    
    public function getText()
    {
        return $this->_options->text;
    }
    
    public function getAttribs()
    {
    	return $this->_options->attribs->toArray();
    }
    
    public function setAttribs($attribs)
    {
        $this->_options->attribs = $attribs;

        return $this;
    }
}