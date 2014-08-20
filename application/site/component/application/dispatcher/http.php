<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Application;

/**
 * Application Http Dispatcher
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Application
 */
class ApplicationDispatcherHttp extends Application\DispatcherHttp
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
     * @param Library\ObjectConfig $config	An optional Library\ObjectConfig object with configuration options.
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the site name
        if(empty($config->site)) {
            $this->_site = $this->getSite();
        } else {
            $this->_site = $config->site;
        }

        $this->addCommandCallback('before.run', 'setLanguage');
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	Library\ObjectConfig $config	An optional Library\ObjectConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'site'     => null,
            'language' => 'en-GB',
        ));

        parent::_initialize($config);
    }

    /**
     * Run the application
     *
     * @param Library\DispatcherContextInterface $context	A dispatcher context object
     */
    protected function _actionRun(Library\DispatcherContextInterface $context)
    {
        define('JPATH_FILES',  APPLICATION_ROOT.'/sites/'. $this->getSite() . '/files');

        // Set timezone to user's setting, falling back to global configuration.
        $timezone = new \DateTimeZone($context->user->get('timezone', $this->getConfig()->timezone));
        date_default_timezone_set($timezone->getName());

        //Route the request
        $this->route();
    }

    /**
     * Route the request
     *
     * @param Library\DispatcherContextInterface $context	A dispatcher context object
     */
    protected function _actionRoute(Library\DispatcherContextInterface $context)
    {
        $url = clone $context->request->getUrl();

        //Parse the route
        $this->getRouter()->parse($url);

        $pages = $this->getObject('application.pages');

        //Redirect the default page
        if(!$context->request->isAjax())
        {
            // Get the route based on the path
            $search = array($context->request->getBasePath(), $this->getSite());
            $route  = trim(str_replace($search, '', $url->toString(Library\HttpURL::PATH + Library\HttpURL::FORMAT)), '/');

            //Redirect to the default menu item if the route is empty
            if(strpos($route, $pages->getHome()->route) === 0 && $context->request->getFormat() == 'html')
            {
                $url = $pages->getHome()->getLink();
                $url->query['Itemid'] = $pages->getHome()->id;

                $this->getRouter()->build($url);

                return $this->redirect($url);
            }
        }

        // Redirect if page type is redirect.
        if(($page = $pages->getActive()) && $page->type == 'redirect')
        {
            if($page->link_id)
            {
                $redirect = $pages->getPage($page->link_id);
                $url      = $redirect->type == 'url' ? $redirect->link_url : $redirect->route;
            }
            else $url = $page->link_url;

            return $this->redirect($url);
        }

        //Set the request
        $context->request->query->add($url->query);

        //Set the controller to dispatch
        if($context->request->query->has('component'))
        {
            $component = $context->request->query->get('component', 'cmd');
            $this->forward($component);
        }
    }

    /**
     * Set the application language
     *
     * @param Library\DispatcherContextInterface $context	A dispatcher context object
     * @return	void
     */
    public function setLanguage(Library\DispatcherContextInterface $context)
    {
        $languages = $this->getObject('application.languages');
        $language  = null;

        // Otherwise use user language setting.
        if(!$language && $iso_code = $context->user->get('language')) {
            $language = $languages->find(array('iso_code' => $iso_code));
        }

        // If no user language specified, use application
        if($iso_code = $this->getConfig()->language) {
            $language = $languages->find(array('iso_code' => $iso_code));
        }

        // If language still not set, use the primary.
        if(!$language) {
            $language = $languages->getPrimary();
        }

        $languages->setActive($language);
    }

    /**
     * Re-create the session if site has changed
     *
     * @return Library\UserInterface
     */
    public function getUser()
    {
        if(!$this->_user instanceof Library\UserInterface)
        {
            $user    =  parent::getUser();
            $session =  $user->getSession();

            //Re-create the session if we changed sites
            if($user->isAuthentic() && ($session->site != $this->getSite()))
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
     * Get the application router.
     *
     * @param  array $options 	An optional associative array of configuration options.
     * @return	\ApplicationRouter
     */
    public function getRouter(array $options = array())
    {
        return $this->getObject('com:application.router', $options);
    }

    /**
     * Gets the name of site
     *
     * This function tries to get the site name based on the information present in the request. If no site can be found
     * it will return 'default'.
     *
     * @param  boolean $reparse Reparse the site name from the request url
     * @return string  The site name
     */
    public function getSite($reparse = false)
    {
        if(!$this->_site || $reparse)
        {
            // Check URL host
            $uri  = clone($this->getRequest()->getUrl());

            $host = $uri->getHost();
            if(!$this->getObject('com:sites.model.sites')->fetch()->find($host))
            {
                // Check folder
                $base = $this->getRequest()->getBaseUrl()->getPath();
                $path = trim(str_replace($base, '', $uri->getPath()), '/');
                if(!empty($path)) {
                    $site = array_shift(explode('/', $path));
                } else {
                    $site = 'default';
                }

                //Check if the site can be found, otherwise use 'default'
                if(!$this->getObject('com:sites.model.sites')->fetch()->find($site)) {
                    $site = 'default';
                }

            } else $site = $host;

            $this->_site = $site;
        }

        return $this->_site;
    }
}
