<?php
/**
 * @package        Koowa_Command
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link         http://www.nooku.org
 */

/**
 * Event Command
 *
 * The event commend will translate the command name to a onCommandName format
 * and let the event dispatcher dispatch to any registered event handlers.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Command
 * @uses        KService
 * @uses        KEventDispatcher
 * @uses        KInflector
 */
class KCommandEvent extends KCommand
{
    /**
     * The event dispatcher object
     *
     * @var KEventDispatcher
     */
    protected $_dispatcher;

    /**
     * Constructor.
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        if (is_null($config->event_dispatcher)) {
            throw new \InvalidArgumentException('event_dispatcher [KEventDispatcherInterface] config option is required');
        }

        $this->_event_dispatcher = $config->event_dispatcher;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'event_dispatcher' => null
        ));

        parent::_initialize($config);
    }

    /**
     * Get the event dispatcher
     *
     * @return  KEventDispatcher
     */
    public function getEventDispatcher()
    {
        if(!$this->_event_dispatcher instanceof KEventDispatcherInterface)
        {
            $this->_event_dispatcher = $this->getService($this->_event_dispatcher);

            //Make sure the request implements KControllerRequestInterface
            if(!$this->_event_dispatcher instanceof KEventDispatcherInterface)
            {
                throw new \UnexpectedValueException(
                    'EventDispatcher: '.get_class($this->_event_dispatcher).' does not implement KEventDispatcherInterface'
                );
            }
        }

        return $this->_event_dispatcher;
    }

    /**
     * Command handler
     *
     * @param   string      The command name
     * @param   object      The command context
     * @return  boolean     Always returns true
     */
    public function execute($name, KCommandContext $context)
    {
        $type = '';

        if ($context->getSubject())
        {
            $identifier = clone $context->getSubject()->getIdentifier();

            if ($identifier->path) {
                $type = array_shift($identifier->path);
            } else {
                $type = $identifier->name;
            }
        }

        $parts = explode('.', $name);
        $name = 'on' . ucfirst(array_shift($parts)) . ucfirst($type) . KInflector::implode($parts);

        $event = new KEvent(clone($context));
        $event->setTarget($context->getSubject());

        $this->getEventDispatcher()->dispatchEvent($name, $event);

        return true;
    }
}