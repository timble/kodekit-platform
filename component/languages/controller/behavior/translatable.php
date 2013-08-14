<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Languages;

use Nooku\Library;

/**
 * Translatable Controller Behavior
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Languages
 */
class ControllerBehaviorTranslatable extends Library\DatabaseBehaviorAbstract
{
    protected function _beforeControllerGet(Library\CommandContext $context)
    {
        $model = $this->getModel();

        if($model->getTable()->isTranslatable())
        {
            $state = $model->getState();
            if(!isset($state->translated)) {
                $state->insert('translated', 'boolean');
            }
        }
    }
}