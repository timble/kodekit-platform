<?php
/**
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Component Controller Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Languages
 */

class ComLanguagesControllerComponent extends ComDefaultControllerView
{
    protected function _actionEdit(KCommandContext $context)
    {
        if($context->request->data->has('id'))
        {
            $this->getService('com://admin/languages.model.tables')
                ->component($context->request->data->get('id', 'int'))
                ->getRowset()
                ->setData(array('enabled' => $context->request->data->get('enabled', 'int')))
                ->save();
        }
    }
}