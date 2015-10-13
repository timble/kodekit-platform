<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

/**
 * Loggable Controller Behavior.
 *
 * This behavior will delegate controller action logging to one or more loggers.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
class ControllerBehaviorLoggable extends Library\ControllerBehaviorAbstract
{
    /**
     * Logger queue.
     *
     * @var KObjectQueue
     */
    private $__queue;

    /**
     * Constructor.
     *
     * @param Library\ObjectConfig $config Configuration options.
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        // Create the logger queue
        $this->__queue = $this->getObject('lib:object.queue');

        // Attach the loggers
        $loggers = Library\ObjectConfig::unbox($config->loggers);

        foreach ($loggers as $key => $value)
        {
            if (is_numeric($key)) {
                $this->attachLogger($value);
            } else {
                $this->attachLogger($key, $value);
            }
        }
    }

    /**
     * Initializes the options for the object.
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param Library\ObjectConfig $config Configuration options.
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'priority' => self::PRIORITY_LOWEST,
            'loggers'  => array(),
        ));

        // Append the default logger if none is set.
        if (!count($config->loggers)) {
            $config->append(array('loggers' => array('com:activities.activity.logger')));
        }

        parent::_initialize($config);
    }

    /**
     * Command handler.
     *
     * @param Library\CommandInterface      $command The command.
     * @param Library\CommandChainInterface $chain   The chain executing the command.
     * @return mixed If a handler breaks, returns the break condition. Returns the result of the handler otherwise.
     */
    final public function execute(Library\CommandInterface $command, Library\CommandChainInterface $chain)
    {
        $action = $command->getName();

        foreach($this->__queue as $logger)
        {
            if (in_array($action, $logger->getActions()))
            {
                $object = $logger->getActivityObject($command);

                if ($object instanceof Library\ModelEntityInterface)
                {
                    $subject = $logger->getActivitySubject($command);
                    $logger->log($action, $object, $subject);
                }
            }
        }
    }

    /**
     * Attach a logger.
     *
     * @param mixed $logger An object that implements ObjectInterface, ObjectIdentifier object or valid identifier
     *                      string.
     * @param array $config An optional associative array of configuration settings.
     * @throws \UnexpectedValueException if the logger does not implement ComActivitiesActivityLoggerInterface.
     * @return ControllerBehaviorLoggable
     */
    public function attachLogger($logger, $config = array())
    {
        $identifier = $this->getIdentifier($logger);

        if (!$this->__queue->hasIdentifier($identifier))
        {
            $logger = $this->getObject($identifier, $config);

            if (!($logger instanceof ActivityLoggerInterface))
            {
                throw new \UnexpectedValueException(
                    "Logger $identifier does not implement ComActivitiesActivityLoggerInterface"
                );
            }

            $this->__queue->enqueue($logger, self::PRIORITY_NORMAL);
        }

        return $this;
    }

    /**
     * Get the behavior name.
     *
     * Hardcode the name to 'loggable'.
     *
     * @return string
     */
    final public function getName()
    {
        return 'loggable';
    }

    /**
     * Get an object handle.
     *
     * Force the object to be enqueued in the command chain.
     *
     * @see execute()
     *
     * @return string A string that is unique, or NULL.
     */
    final public function getHandle()
    {
        return Library\ObjectMixinAbstract::getHandle();
    }
}
