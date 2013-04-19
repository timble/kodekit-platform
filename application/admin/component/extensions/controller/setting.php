<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

use Nooku\Library;

/**
 * Setting Controller
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 */
class ExtensionsControllerSetting extends ApplicationControllerDefault
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'request' => array('view' => 'settings')
        ));

        parent::_initialize($config);
    }

    protected function _actionRead(Library\CommandContext $context)
    {
        $name = ucfirst($this->getView()->getName());

        if(!$this->getModel()->getState()->isUnique()) {
            throw new Library\ControllerExceptionNotFound($name.' Not Found');
        }

        return parent::_actionRead($context);
    }
}