<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2015 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Routable Dispatcher Behavior
 *
 * Redirects the page to the default view
 *
 * @author  Johan Janssens <https://github.com/johanjanssens>
 * @package Koowa\Library\Dispatcher\Behavior
 */
class DispatcherBehaviorRoutable extends DispatcherBehaviorAbstract
{
    /**
     * Redirects the page to the default view
     *
     * @param 	DispatcherContextInterface $context The active command context
     * @return  bool
     */
    protected function _beforeDispatch(DispatcherContextInterface $context)
    {
        $view = $context->request->query->get('view', 'cmd');

        //Redirect if no view information can be found in the request
        if(empty($view))
        {
            $url = clone($context->request->getUrl());
            $url->query['view'] = $this->getController()->getView()->getName();

            $this->redirect($url);

            return false;
        }

        return true;
    }
}