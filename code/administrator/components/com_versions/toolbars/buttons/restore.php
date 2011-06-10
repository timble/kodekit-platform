<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Versions
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Revision Row
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Versions
 */
class ComVersionsToolbarButtonRestore extends KToolbarButtonPost
{
    protected function _initialize(KConfig $config)
    {
        $option	= KRequest::get('get.option', 'cmd');
		$view	= KRequest::get('get.view', 'cmd');

        $config->append(array(
            'attribs' => array(
                'data-action' => 'restore',
                'data-url'    => 'index.php?option='.$option.'&view='.$view.'&trashed=1'
            )
        ));

        parent::_initialize($config);
    }
}