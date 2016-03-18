<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Platform\Application;

use Nooku\Library;
use Nooku\Component\Application;

/**
 * Application Dispatcher
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Application
 */
class Dispatcher extends Application\Dispatcher
{
    public function canDispatch()
    {
        return true;
    }

    protected function _actionDispatch(Library\DispatcherContextInterface $context)
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
