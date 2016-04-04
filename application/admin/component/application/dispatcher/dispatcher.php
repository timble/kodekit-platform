<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Application;

use Kodekit\Library;
use Kodekit\Component\Application;

/**
 * Dispatcher
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Application
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
            $url->query['component'] = $this->getController()->getIdentifier()->getPackage();

            $this->getRouter()->build($url);
            return $this->redirect((string) $url);
        }

        parent::_actionDispatch($context);
    }
}
