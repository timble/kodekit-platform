<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-pages for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Window Controller
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Pages
 */
class ControllerWindow extends Library\ControllerView implements Library\ControllerModellable
{
    /**
     * Model object or identifier
     *
     * @var	string|object
     */
    protected $_model;

    /**
     * Get the controller model
     *
     * @throws  \UnexpectedValueException	If the model doesn't implement the ModelInterface
     * @return  Library\ModelInterface
     */
    public function getModel()
    {
        if(!$this->_model instanceof Library\ModelInterface)
        {
            $config = array(
                'decorators'     => array('lib:model.composite.decorator'),
                'state_defaults' => array(
                    'id' => $this->getObject('pages')->getActive()->id,
                )
            );

            $this->_model = $this->getObject('com:pages.model.pages', $config);
        }

        return $this->_model;
    }

    /**
     * Get the controller context
     *
     * @param   Library\ControllerContextInterface $context Context to cast to a local context
     * @return  Library\ControllerContextModel
     */
    public function getContext(Library\ControllerContextInterface $context = null)
    {
        $context = new Library\ControllerContextModel(parent::getContext($context));

        if($this->getObject('pages')->getActive()) {
            $context->setEntity($this->getModel()->fetch());
        }

        return $context;
    }
}