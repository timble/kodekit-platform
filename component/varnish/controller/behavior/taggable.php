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
    protected function _afterRender(Library\ControllerContextInterface $context)
    {
        $controller = $context->getSubject();
        $model      = $controller->getModel();
        $varnish    = $this->getObject('com:varnish.controller.cache');

        if ($model->getState()->isUnique())
        {
            $entities = $model->fetch();
            $key      = $entities->getIdentityKey();
            $value    = $entities->getProperty($key);

            $varnish->tag((string) $controller->getIdentifier().'#'.$value);
        }
        else  $varnish->tag((string) $controller->getIdentifier());
    }

    protected function _afterAdd(Library\ControllerContextInterface $context)
    {
        if($context->response->getStatusCode() == Library\HttpResponse::CREATED)
        {
            $varnish    = $this->getObject('com:varnish.controller.cache');
            $entity     = $context->result;
            $controller = $context->getSubject();

            $varnish->ban($controller->getIdentifier());
        }
    }

    protected function _afterEdit(Library\ControllerContextInterface $context)
    {
        if($context->response->getStatusCode() == Library\HttpResponse::RESET_CONTENT)
        {
            $varnish    = $this->getObject('com:varnish.controller.cache');
            $entities   = $context->result;
            $controller = $context->getSubject();

            foreach($entities as $entity)
            {
                $key   = $entity->getIdentityKey();
                $value = $entity->getProperty($key);

                $varnish->ban($controller->getIdentifier().'#'.$value);
            }

            $varnish->ban($controller->getIdentifier());
        }
    }

    protected function _afterDelete(Library\ControllerContextInterface $context)
    {
        if($context->response->getStatusCode() == Library\HttpResponse::NO_CONTENT)
        {
            $varnish    = $this->getObject('com:varnish.controller.cache');
            $entities   = $context->result;
            $controller = $context->getSubject();

            foreach($entities as $entity)
            {
                $key   = $entity->getIdentityKey();
                $value = $entity->getProperty($key);

                $varnish->ban($controller->getIdentifier().'#'.$value);
            }

            $varnish->ban($controller->getIdentifier());
        }
    }
}