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
 * POST button class for a toolbar
 * 
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Toolbar
 * @subpackage  Button
 */
abstract class KToolbarButtonPost extends KToolbarButtonAbstract
{
    /**
     * The form token value
     *
     * @var string
     */
    protected $_token_value;
    
    /**
     * The form token name
     *
     * @var string
     */
    protected $_token_name;
    
	/**
     * Constructor.
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct( KConfig $config = null) 
    { 
        parent::__construct($config);
        
        $this->_token_value = $config->token_value;
        $this->_token_name  = $config->token_name;
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
        $config->append(array(
            'token_value' => '',
        	'token_name'  => '_token'
        ));

        parent::_initialize($config);
    }
}