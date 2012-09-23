<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Closurable Controller Behavior Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesControllerBehaviorClosurable extends KDatabaseBehaviorAbstract
{
    protected function _beforeControllerGet(KCommandContext $context)
    {
        $model = $this->getModel();
        if($model->getTable()->isClosurable())
        {
            $state = $model->getState();
            
            if(!isset($state->parent_id)) {
                $state->insert('parent_id', 'int');
            }
            
            if(!isset($state->level)) {
                $state->insert('level', 'int');
            }
        }
    }
}