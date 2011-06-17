<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Controller
 * @subpackage 	Toolba
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Controller Toolbar Command Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Controller
 * @subpackage 	Toolba
 */
class KControllerToolbarCommand extends KConfig
{
 	/**
     * The command name
     *
     * @var string
     */
    protected $_name;
    
    /**
     * Constructor.
     *
     * @param	string 			The command name
     * @param   array|KConfig 	An associative array of configuration settings or a KConfig instance.
     */
    public function __construct( $name, $config = array() )
    { 
        parent::__construct($config);
        
        $this->append(array(
            'icon'       => 'icon-32-'.$name,
            'id'         => $name,
            'label'      => ucfirst($name),
            'title'		 => '',
            'disabled'   => false,
            'attribs'    => array(
                'class'        => array('toolbar'),
            )
        ));
        
        //Set the command name
        $this->_name = $name;
    } 
    
    /**
     * Get the command name
     * 
     * @return string	The command name
     */
    public function getName()
    {
        return $this->_name;
    }
}