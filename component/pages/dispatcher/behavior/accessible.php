<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Accessible Dispatcher Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Pages
 */
class DispatcherBehaviorAccessible extends Library\DispatcherBehaviorAbstract
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'priority'   => self::PRIORITY_HIGH,
        ));

        parent::_initialize($config);
    }

    protected function _beforeDispatch(Library\DispatcherContextInterface $context)
    {
        $itemid = $context->getRequest()->getQuery()->get('Itemid', 'int');

        if($page = $this->getObject('pages')->getPage($itemid))
        {
            if($page->isAccessible() && !$page->canAccess()) {
                throw new Library\ControllerExceptionRequestForbidden('Page Not Accessible');
            }
        }
        else throw new Library\ControllerExceptionResourceNotFound('Page Not Found');
    }
}