<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Banners Table Class - Clients 
 *
 * @author      Cristiano Cucco <cristiano.cucco at gmail dot com>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners    
 */
 
class ComBannersDatabaseTableClients extends KDatabaseTableDefault
{
    public function _initialize(KConfig $config)
    {     
        $config->append(array(
            //'identity_column'    => 'id',
            'base'               => 'bannerclient',
            'name'               => 'bannerclient',
            'behaviors'		     => array('lockable'),
            'column_map'         => array(
                'locked_on' => 'checked_out_time',
                'locked_by' => 'checked_out',
            )
        ));
        
        parent::_initialize($config);
    }
}