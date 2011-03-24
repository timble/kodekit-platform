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
 * Banners Database Table Class
 *
 * @author      Cristiano Cucco <http://nooku.assembla.com/profile/cristiano.cucco>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners    
 */
 
class ComBannersDatabaseTableBanners extends KDatabaseTableDefault
{
    public function _initialize(KConfig $config)
    {
        $sluggable = KFactory::get('lib.koowa.database.behavior.sluggable', 
            array('columns'=>array('name'))
        );
        
        $config->append(array(
            'identity_column'    => 'bid',
            'base'               => 'banner',
            'name'               => 'banner',
            'behaviors'		     => array(
                'creatable', 'lockable', $sluggable,
                'admin::com.banners.database.behavior.orderable'
            ),
            'column_map'         => array(
                'enabled'    => 'showBanner',
                'created_on' => 'date',
                'locked_on'  => 'checked_out_time',
                'locked_by'  => 'checked_out',
                'slug' 		 => 'alias'
            )
        ));
        
        parent::_initialize($config);
    }
}
