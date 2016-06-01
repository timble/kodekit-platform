<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Application;

use Kodekit\Library;
use Kodekit\Component\Application;

/**
 * Application Dispatcher
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Application
 */
class Dispatcher extends Application\Dispatcher
{
    protected function _actionDispatch(Library\DispatcherContext $context)
    {
        $pages = $this->getObject('pages');

        //Redirect the default page
        if(!$context->request->isAjax() && $context->request->getFormat() == 'html')
        {
            // Get the route based on the path
            $search = array($context->request->getBasePath(), $this->getSite());
            $route  = trim(str_replace($search, '', $context->request->getUrl()->getPath()), '/');

            //Redirect to the default menu item if the route is empty
            if(strpos($route, $pages->getDefault()->route) === 0 )
            {
                $url = $pages->getDefault()->getLink();
                $url->query['Itemid'] = $pages->getDefault()->id;

                $this->getRouter()->build($url);

                return $this->redirect($url);
            }
        }

        parent::_actionDispatch($context);
    }
}
