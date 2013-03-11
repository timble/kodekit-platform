<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Debug;

use Nooku\Framework;

/**
 * Debug Controller
 *
 * @author  Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @package Nooku\Component\Debug
 */
class ControllerDebug extends \ComBaseControllerView
{
    protected function _initialize(Framework\Config $config)
    {
        parent::_initialize($config);

        //Don't dispatch event or allow callbacks
        $config->dispatch_events  = false;
        $config->enable_callbacks = false;
    }
}