<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Archive Toolbar Button Class
 * 
 * @author    	Richie Mortimer <http://nooku.assembla.com/profile/ravenlife>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 */

class ComFrontpageToolbarButtonArchive extends ComDefaultToolbarButtonDefault
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'text' => 'Archive',
            'attribs'  => array(
                'data-action' => 'edit',
                'data-data'   => '{state:-1}'
            )
        ));

        parent::_initialize($config);
    }
}