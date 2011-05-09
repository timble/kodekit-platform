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
}