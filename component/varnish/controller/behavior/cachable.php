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