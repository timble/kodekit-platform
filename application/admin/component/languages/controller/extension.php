<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Extension Controller
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Component\Languages
 */
class LanguagesControllerExtension extends Library\ControllerView
{
    protected function _actionEdit(Library\CommandContext $context)
    {
        if($context->request->data->has('id'))
        {
            $this->getObject('com:languages.model.tables')
                ->extension($context->request->data->get('id', 'int'))
                ->getRowset()
                ->setData(array('enabled' => $context->request->data->get('enabled', 'int')))
                ->save();
        }
    }
}