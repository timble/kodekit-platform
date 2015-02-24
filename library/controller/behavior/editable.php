<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Editable Controller Behavior
 *
 * Behavior defines 'save', 'apply' and cancel functions. Functions are only executable if the request format is
 * 'html'. For other formats, eg json use 'edit' and 'read' actions directly.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Controller
 */
class ControllerBehaviorEditable extends ControllerBehaviorAbstract
{
    /**
     * Constructor
     *
     * @param ObjectConfig $config  An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->registerCallback('after.read'  , array($this, 'lockResource'));
        $this->registerCallback('after.save'  , array($this, 'unlockResource'));
        $this->registerCallback('after.cancel', array($this, 'unlockResource'));

        if($this->getRequest()->getFormat() == 'html')
        {
            $this->registerCallback('before.read' , array($this, 'setReferrer'));
            $this->registerCallback('after.apply' , array($this, 'lockReferrer'));
            $this->registerCallback('after.read'  , array($this, 'unlockReferrer'));
            $this->registerCallback('after.save'  , array($this, 'unsetReferrer'));
            $this->registerCallback('after.cancel', array($this, 'unsetReferrer'));
        }
    }

    /**
     * Lock the referrer from updates
     *
     * @param  CommandContext  $context A command context object
     * @return void
     */
    public function lockReferrer(CommandContext $context)
    {
        $cookie = $this->getObject('lib:http.cookie', array(
            'name'   => 'referrer_locked',
            'value'  => true,
            'path'   => $context->request->getBaseUrl()->getPath() ?: '/'
        ));

        $context->response->headers->addCookie($cookie);
    }

    /**
     * Unlock the referrer for updates
     *
     * @param   CommandContext  $context A command context object
     * @return void
     */
    public function unlockReferrer(CommandContext $context)
    {
        $path = $context->request->getBaseUrl()->getPath() ?: '/';
        $context->response->headers->clearCookie('referrer_locked', $path);
    }

    /**
     * Lock the resource
     *
     * Only lock if the context contains a row object and if the user has an active session he can edit or delete the
     * resource. Otherwise don't lock it.
     *
     * @param   CommandContext  $context The command context
     * @return  void
     */
    public function lockResource(CommandContext $context)
    {
        if($this->isLockable() && $this->canEdit()) {
            $context->result->lock();
        }
    }

    /**
     * Unlock the resource
     *
     * @param  CommandContext  $context The command context
     * @return void
     */
    public function unlockResource(CommandContext $context)
    {
        if($this->isLockable() && $this->canEdit()) {
            $context->result->unlock();
        }
    }

    /**
     * Get the referrer
     *
     * @param    CommandContext $context A command context object
     * @return HttpUrl    A HttpUrl object.
     */
    public function getReferrer(CommandContext $context)
    {
        if($context->request->cookies->has('referrer'))
        {
            $referrer = $context->request->cookies->get('referrer', 'url');
            $referrer = $this->getObject('lib:http.url', array('url' => $referrer));
        }
        else $referrer = $this->findReferrer($context);

        return $referrer;
    }

    /**
     * Set the referrer
     *
     * @param    CommandContext $context A command context object
     * @return void
     */
    public function setReferrer(CommandContext $context)
    {
        if (!$context->request->cookies->has('referrer_locked'))
        {
            $request  = $context->request->getUrl();
            $referrer = $context->request->getReferrer();

            //Compare request url and referrer
            if (!isset($referrer) || ((string)$referrer == (string)$request))
            {
                $controller = $this->getMixer();
                $identifier = $controller->getIdentifier();

                $option = 'com_' . $identifier->package;
                $view = StringInflector::pluralize($identifier->name);
                $referrer = $controller->getView()->getRoute('option=' . $option . '&view=' . $view, true, false);
            }

            //Add the referrer cookie
            $cookie = $this->getObject('lib:http.cookie', array(
                'name'   => 'referrer',
                'value'  => $referrer,
                'path'   => $context->request->getBaseUrl()->getPath() ?: '/'
            ));

            $context->response->headers->addCookie($cookie);
        }
    }

    /**
     * Find the referrer based on the context
     *
     * Method is being called when no referrer can be found in the request or when request url and referrer are
     * identical. Function should return a url that is different from the request url to avoid redirect loops.
     *
     * @param ControllerContextInterface $context
     * @return HttpUrl    A HttpUrl object
     */
    public function findReferrer(CommandContext $context)
    {
        $controller = $this->getMixer();
        $identifier = $controller->getIdentifier();

        $component = $identifier->package;
        $view      = StringInflector::pluralize($identifier->name);
        $referrer  = $controller->getView()->getRoute('component=' . $component . '&view=' . $view, true, false);

        return $this->getObject('lib:http.url', array('url' => $referrer));
    }

    /**
     * Unset the referrer
     *
     * @return void
     */
    public function unsetReferrer(CommandContext $context)
    {
        $path = $context->request->getBaseUrl()->getPath() ?: '/';
        $context->response->headers->clearCookie('referrer', $path);
    }

    /**
     * Check if the resource is locked
     *
     * @return bool Returns TRUE if the resource is locked, FALSE otherwise.
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
     * Check if the resource is lockable
     *
     * @return bool Returns TRUE if the resource is can be locked, FALSE otherwise.
     */
    public function isLockable()
    {
        $controller = $this->getMixer();

        if($controller instanceof ControllerModellable)
        {
            if($this->getModel()->getState()->isUnique())
            {
                $entity = $this->getModel()->getRow();

                if($entity->isLockable()) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Permission handler for save actions
     *
     * Method returns TRUE iff the controller implements the ControllerModellable interface.
     *
     * @return  boolean Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canSave()
    {
        if($this->getRequest()->getFormat() == 'html')
        {
            if($this->getModel()->getState()->isUnique())
            {
                if($this->canEdit())
                {
                    if($this->isLockable() && !$this->isLocked()) {
                        return true;
                    }
                }
            }
            else
            {
                if($this->canAdd()) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Permission handler for apply actions
     *
     * Method returns TRUE iff the controller implements the ControllerModellable interface.
     *
     * @return  boolean Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canApply()
    {
       return $this->canSave();
    }

    /**
     * Permission handler for cancel actions
     *
     * Method returns TRUE iff the controller implements the ControllerModellable interface.
     *
     * @return  boolean Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canCancel()
    {
        if($this->getRequest()->getFormat() == 'html') {
            return $this->canRead();
        }

        return false;
    }

    /**
     * Save action
     *
     * This function wraps around the edit or add action. If the model state is unique a edit action will be
     * executed, if not unique an add action will be executed.
     *
     * This function also sets the redirect to the referrer.
     *
     * @param   CommandContext  $context A command context object
     * @return  DatabaseRowInterface     A row object containing the saved data
     */
    protected function _actionSave(CommandContext $context)
    {
        $action = $this->getModel()->getState()->isUnique() ? 'edit' : 'add';
        $entity = $context->getSubject()->execute($action, $context);

        //Create the redirect
        $context->response->setRedirect($this->getReferrer($context));

        return $entity;
    }

    /**
     * Apply action
     *
     * This function wraps around the edit or add action. If the model state is unique a edit action will be
     * executed, if not unique an add action will be executed.
     *
     * This function also sets the redirect to the current url
     *
     * @param    CommandContext  $context A command context object
     * @return   DatabaseRowInterface     A row object containing the saved data
     */
    protected function _actionApply(CommandContext $context)
    {
        $action = $this->getModel()->getState()->isUnique() ? 'edit' : 'add';
        $entity = $context->getSubject()->execute($action, $context);

        //Create the redirect
        $url = $this->getReferrer($context);

        if ($entity instanceof DatabaseRowInterface)
        {
            $url = clone $context->request->getUrl();

            if ($this->getModel()->getState()->isUnique())
            {
                $states = $this->getModel()->getState()->getValues(true);

                foreach ($states as $key => $value) {
                    $url->query[$key] = $entity->get($key);
                }
            }
            else $url->query[$entity->getIdentityColumn()] = $entity->get($entity->getIdentityColumn());
        }

        $context->response->setRedirect($url);

        return $entity;
    }

    /**
     * Cancel action
     *
     * This function will unlock the row(s) and set the redirect to the referrer
     *
     * @param   CommandContext  $context A command context object
     * @return  DatabaseRowInterface    A row object containing the data of the cancelled object
     */
    protected function _actionCancel(CommandContext $context)
    {
        //Create the redirect
        $context->response->setRedirect($this->getReferrer($context));

        $entity = $context->getSubject()->execute('read', $context);
        return $entity;
    }

    /**
     * Add a lock flash message if the resource is locked
     *
     * @param   CommandContext	$context A command context object
     * @return 	void
     */
    protected function _afterControllerRead(CommandContext $context)
    {
        $entity = $context->result;

        //Add the notice if the resource is locked
        if($this->canEdit() && $this->isLockable() && $this->isLocked())
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
     * Prevent editing a locked resource
     *
     * If the resource is locked a Retry-After header indicating the time at which the conflicting edits are expected
     * to complete will be added. Clients should wait until at least this time before retrying the request.
     *
     * @param   CommandContext	$context A command context object
     * @throws  ControllerExceptionConflict If the resource is locked
     * @return 	void
     */
    protected function _beforeControllerEdit(CommandContext $context)
    {
        if($this->isLocked())
        {
            $context->response->headers->set('Retry-After', $context->user->session->getLifetime());
            throw new ControllerExceptionConflict('Resource is locked.');
        }
    }

    /**
     * Prevent deleting a locked resource
     *
     * If the resource is locked a Retry-After header indicating the time at which the conflicting edits are expected
     * to complete will be added. Clients should wait until at least this time before retrying the request.
     *
     * @param   CommandContext	$context A command context object
     * @throws  ControllerExceptionConflict If the resource is locked
     * @return 	void
     */
    protected function _beforeControllerDelete(CommandContext $context)
    {
        if($this->isLocked())
        {
            $context->response->headers->set('Retry-After', $context->user->session->getLifetime());
            throw new ControllerExceptionConflict('Resource is locked');
        }
    }
}