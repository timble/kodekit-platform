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
 * Controller Taggable Behavior
 *
 * @author  Dave Li <http://github.com/daveli>
 * @package Component\Varnish
 */
class ControllerBehaviorTaggable extends Library\BehaviorAbstract
{
    /**
     *  Get the varnish cache controller
     *
     * @return ControllerCache
     */
    public function getCache()
    {
        return $this->getObject('com:varnish.controller.cache');
    }

    protected function _afterRender(Library\ControllerContextInterface $context)
    {
        $controller = $context->getSubject();
        $model      = $controller->getModel();

        if ($model->getState()->isUnique())
        {
            $entities = $model->fetch();
            $key      = $entities->getIdentityKey();
            $value    = $entities->getProperty($key);

            $this->getCache()->tag((string) $controller->getIdentifier().'#'.$value);
        }
        else  $this->getCache()->tag((string) $controller->getIdentifier());
    }

    protected function _afterAdd(Library\ControllerContextInterface $context)
    {
        if($context->response->getStatusCode() == Library\HttpResponse::CREATED)
        {
            $varnish    = $this->getCache();
            $entity     = $context->result;
            $controller = $context->getSubject();

            $varnish->ban($controller->getIdentifier());
        }
    }

    protected function _afterEdit(Library\ControllerContextInterface $context)
    {
        if($context->response->getStatusCode() == Library\HttpResponse::RESET_CONTENT)
        {
            $varnish    = $this->getCache();
            $entities   = $context->result;
            $controller = $context->getSubject();

            foreach($entities as $entity)
            {
                $key   = $entity->getIdentityKey();
                $value = $entity->getProperty($key);

                $this->getCache()->ban($controller->getIdentifier().'#'.$value);
            }

            $this->getCache()->ban($controller->getIdentifier());
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

                $this->getCache()->ban($controller->getIdentifier().'#'.$value);
            }

            $this->getCache()->ban($controller->getIdentifier());
        }
    }
}