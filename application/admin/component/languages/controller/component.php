<?php
/**
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Component Controller Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Languages
 */

class LanguagesControllerComponent extends Library\ControllerView
{
    protected function _actionEdit(Library\CommandContext $context)
    {
        if($context->request->data->has('id'))
        {
            $this->getObject('com:languages.model.tables')
                ->component($context->request->data->get('id', 'int'))
                ->getRowset()
                ->setData(array('enabled' => $context->request->data->get('enabled', 'int')))
                ->save();
        }
    }
}