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
 * Activity Logger.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
class ActivityLogger extends Library\Object implements ActivityLoggerInterface
{
    /**
     * List of actions to log.
     *
     * @var array
     */
    protected $_actions;

    /**
     * The name of the column to use as the title column in the activity entry.
     *
     * @var string
     */
    protected $_title_column;

    /**
     * Activity controller identifier.
     *
     * @param string|Library\ObjectIdentifierInterface
     */
    protected $_controller;

    /**
     * Constructor.
     *
     * @param Library\ObjectConfig $config Configuration options.
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_title_column = Library\ObjectConfig::unbox($config->title_column);
        $this->_controller   = $config->controller;

        $this->setActions(Library\ObjectConfig::unbox($config->actions));
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
            'actions'      => array('after.edit', 'after.add', 'after.delete'),
            'title_column' => array('title', 'name'),
            'controller'   => 'com:activities.controller.activity'
        ));

        parent::_initialize($config);
    }

    /**
     * Log an activity.
     *
     * @param string                     $action  The action to log.
     * @param Library\ModelEntityInterface      $object  The activity object on which the action is performed.
     * @param Library\ObjectIdentifierInterface $subject The activity subject who is performing the action.
     */
    public function log($action, Library\ModelEntityInterface $object, Library\ObjectIdentifierInterface $subject)
    {
        $controller = $this->getObject($this->_controller);

        if($controller instanceof Library\ControllerModellable)
        {
            foreach($object as $entity)
            {
                // Only log if the entity status is valid.
                $status = $this->getActivityStatus($entity, $action);

                if (!empty($status) && $status !== Library\ModelEntityInterface::STATUS_FAILED)
                {
                    // Get the activity data
                    $data = $this->getActivityData($entity, $subject);

                    // Set the status
                    if(!isset($data['status'] )) {
                        $data['status'] = $status;
                    }

                    // Set the action
                    if(!isset($data['action']))
                    {
                        $parts = explode('.', $action);
                        $data['action'] = $parts[1];
                    }

                    $controller->add($data);
                }
            }
        }
    }

    /**
     * Return the list of actions the logger should listen to for logging.
     *
     * @return array The list of actions.
     */
    public function getActions()
    {
        return $this->_actions;
    }

    /**
     * Set the list of actions the logger should listen to for logging.
     *
     * @param array $actions The list of actions.
     * @return ActivityLoggerInterface
     */
    public function setActions($actions)
    {
        $this->_actions = $actions;
        return $this;
    }

    /**
     * Get the activity object.
     *
     * The activity object is the entity on which the action is executed.
     *
     * @param Library\CommandInterface $command The command.
     * @return Library\ModelEntityInterface The activity object.
     */
    public function getActivityObject(Library\CommandInterface $command)
    {
        $parts = explode('.', $command->getName());

        // Properly fetch data for the event.
        if ($parts[0] == 'before') {
            $object = $command->getSubject()->getModel()->fetch();
        } else {
            $object = $command->result;
        }

        return $object;
    }

    /**
     * Get the activity status.
     *
     * The activity status is the current status of the activity object.
     *
     * @param Library\ModelEntityInterface $object The activity object.
     * @param string                       $action The action being executed.
     * @return string The activity status.
     */
    public function getActivityStatus(Library\ModelEntityInterface $object, $action = null)
    {
        $status = $object->getStatus();

        // Commands may change the original status of an action.
        if ($action == 'after.add' && $status == Library\ModelEntityInterface::STATUS_UPDATED) {
            $status = ModelEntityInterface::STATUS_CREATED;
        }

        // Ignore non-changing edits.
        if ($action == 'after.edit' && $status == Library\ModelEntityInterface::STATUS_FETCHED) {
            $status = null;
        }

        return $status;
    }

    /**
     * Get the activity subject.
     *
     * The activity subject is the identifier of the object that executes the action.
     *
     * @param Library\CommandInterface $command The command.
     * @return Library\ObjectIdentifier The activity subject.
     */
    public function getActivitySubject(Library\CommandInterface $command)
    {
        return $command->getSubject()->getIdentifier();
    }

    /**
     * Get the activity data.
     *
     * @param Library\ModelEntityInterface      $object  The activity object.
     * @param Library\ObjectIdentifierInterface $subject The activity subject.
     * @return array Activity data.
     */
    public function getActivityData(Library\ModelEntityInterface $object, Library\ObjectIdentifierInterface $subject)
    {
        $data = array(
            'application' => $subject->domain,
            'type'        => $subject->type,
            'package'     => $subject->package,
            'name'        => $subject->name,
        );

        if (is_array($this->_title_column))
        {
            foreach ($this->_title_column as $title)
            {
                if ($object->{$title})
                {
                    $data['title'] = $object->{$title};
                    break;
                }
            }
        }
        elseif ($object->{$this->_title_column}) {
            $data['title'] = $object->{$this->_title_column};
        }

        if (!isset($data['title'])) {
            $data['title'] = '#' . $object->id;
        }

        $data['row'] = $object->id;

        return $data;
    }
}
