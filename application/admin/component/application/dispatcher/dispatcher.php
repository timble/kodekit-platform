<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Application;

/**
 * Dispatcher
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Application
 */
class ApplicationDispatcher extends Application\Dispatcher
{
    /**
     * The site identifier.
     *
     * @var string
     */
    protected $_site;

    /**
     * Constructor.
     *
     * @param Library\ObjectConfig $config An optional Library\ObjectConfig object with configuration options.
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the site name
        if (empty($config->site)) {
            $this->_site = $this->getSite();
        } else {
            $this->_site = $config->site;
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param    Library\ObjectConfig $config An optional Library\ObjectConfig object with configuration options.
     *
     * @return    void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'component' => 'dashboard',
            'request' => array(
                'base_url'  => '/administrator',
            ),
            'site'      => null,
        ));

        parent::_initialize($config);
    }

    /**
     * Permission handler for dispatch actions
     *
     * @return  boolean  Return TRUE if action is permitted. FALSE otherwise.
     */
    public function canDispatch()
    {
        return true;
    }

    /**
     * Re-create the session if site has changed
     *
     * @return Library\UserInterface
     */
    public function getUser()
    {
        if (!$this->_user instanceof Library\UserInterface)
        {
            $user    = parent::getUser();
            $session = $user->getSession();

            //Re-create the session if we changed sites
            if ($user->isAuthentic() && ($session->site != $this->getSite()))
            {
                //@TODO : Fix this
                //if(!$this->getObject('com:users.controller.session')->add()) {
                //    $session->destroy();
                //}
            }
        }

        return parent::getUser();
    }

    /**
     * Gets the name of site
     *
     * This function tries to get the site name based on the information present in the request. If no site can be found
     * it will return 'default'.
     *
     * @return string  The site name
     */
    public function getSite()
    {
        if (!$this->_site)
        {
            // Check URL host
            $uri = clone($this->getRequest()->getUrl());

            $host = $uri->getHost();
            if (!$this->getObject('application.sites')->find($host))
            {
                // Check folder
                $base = $this->getRequest()->getBaseUrl()->getPath();
                $path = trim(str_replace($base, '', $uri->getPath()), '/');
                if (!empty($path)) {
                    $site = array_shift(explode('/', $path));
                } else {
                    $site = 'default';
                }

                //Check if the site can be found, otherwise use 'default'
                if (!$this->getObject('application.sites')->find($site)) {
                    $site = 'default';
                }

            } else $site = $host;

            $this->_site = $site;
        }

        return $this->_site;
    }

    /**
     * Dispatch the application
     *
     * @param Library\DispatcherContextInterface $context A dispatcher context object
     */
    protected function _actionDispatch(Library\DispatcherContextInterface $context)
    {
        //Redirect if no view information can be found in the request
        if(!$context->request->query->has('component'))
        {
            $url = clone($context->request->getUrl());
            $url->query['component'] = $this->getConfig()->component;

            $this->getRouter()->build($url);
            return $this->redirect((string) $url);
        }

        parent::_actionDispatch($context);
    }
}
