<?php
/**
 * @package     Koowa_Controller
 * @subpackage  Behavior
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Lockable Controller Behavior
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage  Behavior
 */
class ControllerBehaviorLockable extends ControllerBehaviorAbstract
{
    /**
     * Constructor
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->registerCallback('after.read'  , array($this, 'lock'));
        $this->registerCallback('after.save'  , array($this, 'unlock'));
        $this->registerCallback('after.cancel', array($this, 'unlock'));
    }

    /**
     * Lock the entity
     *
     * Only lock if the context contains a row object and if the user has an active session he can edit or delete the
     * entity. Otherwise don't lock it.
     *
     * @param   CommandContext  $context The command context
     * @return  void
     */
    public function lock(CommandContext $context)
    {
        if($this->isLockable() && $this->canEdit()) {
            $context->result->lock();
        }
    }

    /**
     * Unlock the entity
     *
     * @param  CommandContext  $context The command context
     * @return void
     */
    public function unlock(CommandContext $context)
    {
        if($this->isLockable() && $this->canEdit()) {
            $context->result->unlock();
        }
    }

    /**
     * Check if the entity is locked
     *
     * @return bool Returns TRUE if the entity is locked, FALSE otherwise.
     */
    public function isLocked()
    {
        if($this->getModel()->getState()->isUnique())
        {
            $entity = $this->getModel()->getRow();

            if($entity->isLockable() && $entity->isLocked()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the entity is lockable
     *
     * @return bool Returns TRUE if the entity is can be locked, FALSE otherwise.
     */
    public function isLockable()
    {
        if($this->getModel()->getState()->isUnique())
        {
            $entity = $this->getModel()->getRow();

            if($entity->isLockable()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add a lock flash message if the entity is locked
     *
     * @param   CommandContext	$context A command context object
     * @return 	void
     */
    protected function _afterControllerRead(CommandContext $context)
    {
        $entity = $context->result;

        //Add the notice if the entity is locked
        if($this->isLockable() && $this->isLocked())
        {
            //Prevent a re-render of the message
            if($context->request->getUrl() != $context->request->getReferrer())
            {
                $user = $this->getObject('com:users.database.row.user')
                    ->set('id', $entity->locked_by)
                    ->load();

                $date    = new Date(array('date' => $entity->locked_on));
                $message = \JText::sprintf('Locked by %s %s', $user->get('name'), $date->humanize());

                $context->response->addMessage($message, 'notice');
            }
        }
    }

    /**
     * Prevent editing a locked entity
     *
     * If the entity is locked a Retry-After header indicating the time at which the conflicting edits are expected
     * to complete will be added. Clients should wait until at least this time before retrying the request.
     *
     * @param   CommandContext	$context A command context object
     * @throws  ControllerExceptionConflict If the entity is locked
     * @return 	void
     */
    protected function _beforeControllerEdit(CommandContext $context)
    {
        if($this->isLocked())
        {
            $context->response->headers->set('Retry-After', $context->user->session->getLifetime());
            throw new ControllerExceptionConflict('Entity is locked.');
        }
    }

    /**
     * Prevent deleting a locked entity
     *
     * If the entity is locked a Retry-After header indicating the time at which the conflicting edits are expected
     * to complete will be added. Clients should wait until at least this time before retrying the request.
     *
     * @param   CommandContext	$context A command context object
     * @throws  ControllerExceptionConflict If the entity is locked
     * @return 	void
     */
    protected function _beforeControllerDelete(CommandContext $context)
    {
        if($this->isLocked())
        {
            $context->response->headers->set('Retry-After', $context->user->session->getLifetime());
            throw new ControllerExceptionConflict('Entity is locked');
        }
    }
}