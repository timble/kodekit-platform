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
    protected $_varnish;

    protected $_entity_properties;

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the columns
        $this->_entity_properties = $config->entity_properties;

        //Connect to Varnish
        if(!isset($this->_varnish))
        {
            $this->_varnish = $this->getObject('com:varnish.model.sockets');
            $this->_varnish->connect();
        }
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'entity_properties'  => array('enabled', 'published', 'ordering')
        ));

        parent::_initialize($config);
    }

    protected function _afterAdd(Library\ControllerContextInterface $context)
    {
        $identifier = $this->getMixer()->getIdentifier();

        $this->_varnish->ban('obj.http.x-entities == '. $identifier);
    }

    protected function _beforeEdit(Library\ControllerContextInterface $context)
    {
        $identifier = $this->getMixer()->getIdentifier();

        $modified  = $this->getModel()->getTable()->filter($context->request->data->toArray());

        foreach($this->_entity_properties as $property)
        {
            if (array_key_exists($property, $modified))
            {
                $this->_varnish->ban('obj.http.x-entities == '. $identifier);
                break;
            }
        }
    }

    protected function _afterEdit(Library\ControllerContextInterface $context)
    {
        $entity     = $context->result;

        $modified   = $this->getModel()->getTable()->filter($context->request->data->toArray());
        $identifier = $this->getMixer()->getIdentifier();

        if($modified) {
            $this->_varnish->ban('obj.http.x-entities ~ '. $identifier.':'.$entity->id);
        }
    }
}