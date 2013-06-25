<?php
/**
 * @package     Nooku_Server
 * @subpackage  Terms
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Library;
use Nooku\Component\Terms;

/**
 * Term Controller Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Terms
 */
abstract class TermsControllerTerm extends Terms\ControllerTerm
{ 
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
        	//'behaviors' => array('com:activities.controller.behavior.loggable'),
        ));
        
        parent::_initialize($config);
    }
}