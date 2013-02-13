<?php
/**
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Components Database Table Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Extensions    
 */
class ComExtensionsDatabaseTableComponents extends KDatabaseTableDefault
{
    public function  _initialize(KConfig $config) 
    {
        $config->append(array(
            'filters'  => array('params' => 'ini')
        ));
        
        parent::_initialize($config);
    }
}