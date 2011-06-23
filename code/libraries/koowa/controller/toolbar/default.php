<?php
/**
 * @version      $Id$
 * @category		Koowa
 * @package     Koowa_Controller
 * @subpackage 	Toolba
 * @copyright    Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Default Controller Toolbar Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Controller
 * @subpackage 	Toolba
 */
class KControllerToolbarDefault extends KControllerToolbarAbstract
{
	/**
     * New toolbar command
     * 
     * @param   object  A KControllerToolbarCommand object
     * @return  void
     */
    protected function _commandNew(KControllerToolbarCommand $command)
    {
        $option = $this->_identifier->package;
        $view   = KInflector::singularize($this->_identifier->name);
        
        $command->append(array(
            'attribs' => array(
                'href'     => JRoute::_( 'index.php?option=com_'.$option.'&view='.$view)
            )
        ));
    }
}