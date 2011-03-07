<?php
/**
* @version      $Id$
* @category		Koowa
* @package		Koowa_Toolbar
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
*/

/**
 * Default Toolbar class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Toolbar
 */
class KToolbarDefault extends KToolbarAbstract
{
    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        $app        = $this->_identifier->application;
        $package    = $this->_identifier->package;
        $name       = $this->_identifier->name;
        
        if(KInflector::isPlural($name))
        {        
            //Create the toolbar
            $this->append('new')
                 ->append('delete');    
        }
        else
        {
            // Create the toolbar
            $this->append('save')
                 ->append('apply')
                 ->append('cancel');
        }
    }
}