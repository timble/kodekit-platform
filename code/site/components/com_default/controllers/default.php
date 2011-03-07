<?php
/**
 * @version     $Id: default.php 2721 2010-10-27 00:58:51Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default View Controller
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultControllerDefault extends KControllerView
{
    /**
     * Set the request information
     * 
     * This function translates 'limitstart' to 'offset' for compatibility with Joomla
     *
     * @param array An associative array of request information
     * @return KControllerBread
     */
    public function setRequest(array $request = array())
    {
        if(isset($request['limitstart'])) {
            $request['offset'] = $request['limitstart'];
        }
        
        $this->_request = new KConfig($request);
        return $this;
    }
    
    /**
     * Generic display function
     * 
     * This function wraps around the read or browse action. If the model state is
     * unique a read action will be executed, if not unique a browse action will be 
     * executed.
     *
     * @param   KCommandContext A command context object
     * @return  KDatabaseRow(set)   A row(set) object containing the data to display
     */
    protected function _actionDisplay(KCommandContext $context)
    {
        //Load the language file for HMVC requests who are not routed through the dispatcher
        if($this->_request->option != $this->getIdentifier()->package) {
            KFactory::get('lib.joomla.language')->load($this->_request->option); 
        }
        
        return parent::_actionDisplay($context);
    }
}