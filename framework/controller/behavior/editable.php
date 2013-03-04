<?php
/**
 * @package     Koowa_Controller
 * @subpackage  Behavior
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Editable Controller Behavior Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage  Behavior
 */
class KControllerBehaviorEditable extends KControllerBehaviorAbstract
{
    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->registerCallback('before.read' , array($this, 'setReferrer'));
        $this->registerCallback('after.apply' , array($this, 'lockReferrer'));
        $this->registerCallback('after.read'  , array($this, 'unlockReferrer'));
        $this->registerCallback('after.save'  , array($this, 'unsetReferrer'));
        $this->registerCallback('after.cancel', array($this, 'unsetReferrer'));
    }

    /**
     * Lock the referrer from updates
     *
     * @param    KCommandContext    A command context object
     * @return void
     */
    public function lockReferrer(KCommandContext $context)
    {
        $cookie = $this->getService('lib://nooku/http.cookie', array(
            'name'   => 'referrer_locked',
            'value'  => true,
            'path'   => $context->request->getBaseUrl()->getPath()
        ));

        $context->response->headers->addCookie($cookie);
    }

    /**
     * Unlock the referrer for updates
     *
     * @param    KCommandContext    A command context object
     * @return void
     */
    public function unlockReferrer(KCommandContext $context)
    {
        $path = $context->request->getBaseUrl()->getPath();
        $context->response->headers->clearCookie('referrer_locked', $path);
    }

    /**
     * Get the referrer
     *
     * @param    KCommandContext    A command context object
     * @return KHttpUrl    A KHttpUrl object.
     */
    public function getReferrer(KCommandContext $context)
    {
        $identifier = $this->getMixer()->getIdentifier();

        $referrer = $this->getService('lib://nooku/http.url',
            array('url' => $context->request->cookies->get('referrer', 'url'))
        );

        return $referrer;
    }

    /**
     * Set the referrer
     *
     * @param    KCommandContext    A command context object
     * @return void
     */
    public function setReferrer(KCommandContext $context)
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
                $view = KInflector::pluralize($identifier->name);
                $referrer = $controller->getView()->getRoute('option=' . $option . '&view=' . $view, true, false);
            }

            //Add the referrer cookie
            $cookie = $this->getService('lib://nooku/http.cookie', array(
                'name'   => 'referrer',
                'value'  => $referrer,
                'path'   => $context->request->getBaseUrl()->getPath()
            ));

            $context->response->headers->addCookie($cookie);
        }
    }

    /**
     * Unset the referrer
     *
     * @return void
     */
    public function unsetReferrer(KCommandContext $context)
    {
        $path = $context->request->getBaseUrl()->getPath();
        $context->response->headers->clearCookie('referrer', $path);
    }

    /**
     * Save action
     *
     * This function wraps around the edit or add action. If the model state is unique a edit action will be
     * executed, if not unique an add action will be executed.
     *
     * This function also sets the redirect to the referrer.
     *
     * @param   KCommandContext  A command context object
     * @return  KDatabaseRow     A row object containing the saved data
     */
    protected function _actionSave(KCommandContext $context)
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
     * @param    KCommandContext    A command context object
     * @return     KDatabaseRow     A row object containing the saved data
     */
    protected function _actionApply(KCommandContext $context)
    {
        $action = $this->getModel()->getState()->isUnique() ? 'edit' : 'add';
        $entity = $context->getSubject()->execute($action, $context);

        //Create the redirect
        $url = $this->getReferrer($context);

        if ($entity instanceof KDatabaseRowInterface)
        {
            $url = clone $context->request->getUrl();

            if ($this->getModel()->getState()->isUnique())
            {
                $states = $this->getModel()->getState()->toArray(true);

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
     * @param    KCommandContext    A command context object
     * @return     KDatabaseRow    A row object containing the data of the cancelled object
     */
    protected function _actionCancel(KCommandContext $context)
    {
        //Create the redirect
        $context->response->setRedirect($this->getReferrer($context));

        $entity = $context->getSubject()->execute('read', $context);
        return $entity;
    }
}