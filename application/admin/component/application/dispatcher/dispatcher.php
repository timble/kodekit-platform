<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Platform\Application;

use Nooku\Library;
use Nooku\Component\Application;

/**
 * Dispatcher
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Application
 */
class Dispatcher extends Application\Dispatcher
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'controller' => 'com:dashboard.dispatcher',
            'request'    => array(
                'base_url'  => '/administrator',
            ),
        ));

        parent::_initialize($config);
    }

    public function canDispatch()
    {
        return true;
    }

    protected function _actionDispatch(Library\DispatcherContextInterface $context)
    {
        //Redirect if no view information can be found in the request
        if(!$context->request->query->has('component'))
        {
            $url = clone($context->request->getUrl());
            $url->query['component'] = $this->getConfig()->component;

            $this->getRouter()->build($url);
            return $this->redirect((string) $url);
        }

        parent::_actionDispatch($context);
    }
}
