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

class LanguagesControllerComponent extends ApplicationControllerDefault
{
    protected function _actionEdit(Library\CommandContext $context)
    {
        $entity = $this->getService('com:languages.model.tables')
            ->component($context->request->data->get('id', 'int'))
            ->getRowset();

        if(count($entity))
        {
            $entity->setData(array('enabled' => $context->request->data->get('enabled', 'int')));

            //Only set the reset content status if the action explicitly succeeded
            if($entity->save() === true) {
                $context->response->setStatus(self::STATUS_RESET);
            } else {
                $context->response->setStatus(self::STATUS_UNCHANGED);
            }
        }
        else throw new ControllerExceptionNotFound('Resource could not be found');

        return $entity;
    }
}