<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Weblinks Toolbar Class
 *
 * @author    	Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 */
class ComWeblinksControllerToolbarWeblink extends ComDefaultControllerToolbarDefault
{
    public function onAfterControllerBrowse(KEvent $event)
    {
        parent::onAfterControllerBrowse($event);

        $this->addSeparator()
             ->addEnable(array('label' => 'publish'))
             ->addDisable(array('label' => 'unpublish'));
    }
}