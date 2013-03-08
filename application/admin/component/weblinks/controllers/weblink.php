<?php
/**
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Weblink Controller Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 */
class ComWeblinksControllerWeblink extends ComBaseControllerModel
{ 
    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
        	'behaviors' => array('com://admin/activities.controller.behavior.loggable'),
        ));
    
        parent::_initialize($config);
    }
}