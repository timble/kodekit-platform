<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright   Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link        https://github.com/timble/kodekit-varnish for the canonical source repository
 */

namespace Kodekit\Component\Varnish;

use Kodekit\Library;

/**
 * Controller Cachable Behavior
 *
 * @author  Dave Li <http://github.com/daveli>
 * @package Component\Varnish
 */
class ControllerBehaviorCachable extends Library\BehaviorAbstract
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

        if($controller instanceof Library\ControllerModellable)
        {
            $model  = $controller->getModel();
            $entity = $model->fetch();

            if ($model->getState()->isUnique()) {
                $this->getCache()->tag($this->_createTag($entity, true));
            } else {
                $this->getCache()->tag($this->_createTag($entity));
            }
        }
    }

    protected function _afterAdd(Library\ControllerContextInterface $context)
    {
        if($context->response->getStatusCode() == Library\HttpResponse::CREATED)
        {
            $entity = $context->result;

            $this->getCache()->ban($this->_createTag($entity));
        }
    }

    protected function _afterEdit(Library\ControllerContextInterface $context)
    {
        if($context->response->getStatusCode() == Library\HttpResponse::RESET_CONTENT)
        {
            $entities = $context->result;

            foreach($entities as $entity) {
                $this->getCache()->ban($this->_createTag($entity, true));
            }

            $this->getCache()->ban($this->_createTag($entity));
        }
    }

    protected function _afterDelete(Library\ControllerContextInterface $context)
    {
        if($context->response->getStatusCode() == Library\HttpResponse::NO_CONTENT)
        {
            $entities = $context->result;

            foreach($entities as $entity) {
                $this->getCache()->ban($this->_createTag($entity, true));
            }

            $this->getCache()->ban($this->_createTag($entity));
        }
    }

    protected function _createTag(Library\ModelEntityInterface $entity, $unique = false)
    {
        $package = $entity->getIdentifier()->package;
        $name    = $entity->getIdentifier()->name;

        $tag = 'com:'.$package.'.'.$name;

        if($unique)
        {
            $key  = $entity->getIdentityKey();
            $tag .= '#'.$entity->getProperty($key);
        }

        return $tag;
    }
}