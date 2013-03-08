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
 * Setting Controller
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Extensions
 */
class ComExtensionsControllerSetting extends ComBaseControllerModel
{
    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
            'request' => array('view' => 'settings')
        ));

        parent::_initialize($config);
    }

    protected function _actionRead(Framework\CommandContext $context)
    {
        $name = ucfirst($this->getView()->getName());

        if(!$this->getModel()->getState()->isUnique()) {
            throw new Framework\ControllerExceptionNotFound($name.' Not Found');
        }

        return parent::_actionRead($context);
    }
}