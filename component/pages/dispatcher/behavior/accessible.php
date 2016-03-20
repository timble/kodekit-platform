<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-pages for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Accessible Dispatcher Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Pages
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