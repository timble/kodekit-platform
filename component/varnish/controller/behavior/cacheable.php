<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Varnish;

use Nooku\Library;

/**
 * Dispatcher Varnishable Behavior
 *
 * @author  Dave Li <http://github.com/daveli>
 * @package Component\Varnish
 */
class ControllerBehaviorCacheable extends Library\BehaviorAbstract
{
    private $__varnish;

    public function getVarnish()
    {
        if(!isset($this->__varnish))
        {
            $this->__varnish = $this->getObject('com:varnish.model.sockets');
            $this->__varnish->connect();
        }

        return $this->__varnish;
    }

    protected function _afterAdd(Library\ControllerContextInterface $context)
    {
        if($context->response->getStatusCode() == Library\HttpResponse::CREATED)
        {
            $entity     = $context->result;
            $controller = $context->getSubject();

            $this->getVarnish()->ban('obj.http.x-entities == '. $controller->getIdentifier());
        }
    }

    protected function _afterEdit(Library\ControllerContextInterface $context)
    {
        if($context->response->getStatusCode() == Library\HttpResponse::RESET_CONTENT)
        {
            $entities  = $context->result;
            $controller = $context->getSubject();

            foreach($entities as $entity)
            {
                $key   = $entity->getIdentityKey();
                $value = $entity->getProperty($key);

                $this->getVarnish()->ban('obj.http.x-entities ~ '. $controller->getIdentifier().'#'.$value);
            }

            $this->getVarnish()->ban('obj.http.x-entities == '. $controller->getIdentifier());
        }
    }

    protected function _afterDelete(Library\ControllerContextInterface $context)
    {
        if($context->response->getStatusCode() == Library\HttpResponse::NO_CONTENT)
        {
            $entities   = $context->result;
            $controller = $context->getSubject();

            foreach($entities as $entity)
            {
                $key   = $entity->getIdentityKey();
                $value = $entity->getProperty($key);

                $this->getVarnish()->ban('obj.http.x-entities ~ '. $controller->getIdentifier().'#'.$value);
            }

            $this->getVarnish()->ban('obj.http.x-entities == '. $controller->getIdentifier());
        }
    }
}