<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

use Nooku\Framework;

/**
 * Site Database Rowset
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Sites
 */
class ComSitesDatabaseRowsetSites extends Framework\DatabaseRowsetAbstract
{       
    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
            'identity_column'   => 'name'
        ));
        
        parent::_initialize($config);
    }
}