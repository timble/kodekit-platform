<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Closurable Controller Behavior Class
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Pages
 */
class ControllerBehaviorClosurable extends Library\DatabaseBehaviorAbstract
{
    protected function _beforeControllerGet(Library\CommandContext $context)
    {
        $model = $this->getModel();
        if($model->getTable()->isClosurable())
        {
            $state = $model->getState();
            
            if(!isset($state->parent)) {
                $state->insert('parent', 'int');
            }
            
            if(!isset($state->level)) {
                $state->insert('level', 'int');
            }
        }
    }
}