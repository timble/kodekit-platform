<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Weblinks;

use Nooku\Framework;

/**
 * Weblink Controller Toolbar
 *
 * @author  Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @package Nooku\Component\Weblinks
 */
class ControllerToolbarWeblink extends Framework\ControllerToolbarModel
{
    public function onAfterControllerBrowse(Framework\Event $event)
    {
        parent::onAfterControllerBrowse($event);

        $this->addSeparator();
        $this->addEnable(array('label' => 'publish'));
        $this->addDisable(array('label' => 'unpublish'));
    }
}