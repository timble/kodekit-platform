<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Delete Toolbar Button Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files   
 */

class ComFilesToolbarButtonDelete extends KToolbarButtonAbstract
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
			'text' => JText::_('Delete'),
        	'icon' => 'icon-32-delete'
        ));
        parent::_initialize($config);
    }

    public function getLink()
    {
    	return '#';
    }
}