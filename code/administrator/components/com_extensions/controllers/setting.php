<?php
/**
 * @version     $Id: default.php 3314 2012-02-10 02:14:52Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Settings
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Setting Controller Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Settings
 */

class ComExtensionsControllerSetting extends ComDefaultControllerDefault
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'request' => array('view' => 'settings')
        ));

        parent::_initialize($config);
    }

    protected function _actionRead(KCommandContext $context)
    {
        $name = ucfirst($this->getView()->getName());

        if(!$this->getModel()->getState()->isUnique()) {
            $context->response->setStatus(KHttpResponse::NOT_FOUND, $name.' Not Found');
        }

        return parent::_actionRead($context);
    }
}