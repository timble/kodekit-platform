<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Http Dispatcher
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Application
 */
class ApplicationDispatcherHttp extends Library\DispatcherAbstract implements Library\ObjectInstantiable
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

        //Set the base url in the request
        $this->getRequest()->setBaseUrl($config->base_url);

        //Register the default exception handler
        $this->addEventListener('onException', array($this, 'exception'), Library\Event::PRIORITY_LOW);

        //Set the site name
        if(empty($config->site)) {
            $this->_site = $this->getSite();
        } else {
            $this->_site = $config->site;
        }

        $this->loadConfig();

        $this->registerCallback('before.run', array($this, 'loadLanguage'));
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	Library\ObjectConfig    $config  An optional Library\ObjectConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'controller'        => 'page',
            'base_url'          => '/administrator',
            'event_subscribers' => array('com:application.event.subscriber.unauthorized'),
            'site'     => null,
            'options'  => array(
                'session_name' => 'admin',
                'config_file'  => JPATH_ROOT.'/config/config.php',
                'language'     => null,
                'theme'        => 'default'
            ),
        ));

        parent::_initialize($config);
    }

    public static function getInstance(Library\ObjectConfig $config, Library\ObjectManagerInterface $manager)
    {
        // Check if an instance with this identifier already exists
        if (!$manager->isRegistered('application'))
        {
            $classname = $config->object_identifier->classname;
            $instance  = new $classname($config);
            $manager->setObject($config->object_identifier, $instance);

            //Add the service alias to allow easy access to the singleton
            $manager->registerAlias('application', $config->object_identifier);
        }

        return $manager->getObject('application');
    }

    /**
     * Run the application
     *
     * @param Library\CommandContext $context	A command context object
     */
    protected function _actionRun(Library\CommandContext $context)
    {
        //Set the site error reporting
        $this->getEventDispatcher()->setDebugMode($this->getCfg('debug_mode'));

        //Set the paths
        $params = $this->getObject('application.extensions')->files->params;

        define('JPATH_FILES'  , JPATH_SITES.'/'.$this->getSite().'/files');
        define('JPATH_CACHE'  , $this->getCfg('cache_path', JPATH_ROOT.'/cache'));

        // Set timezone to user's setting, falling back to global configuration.
        $timezone = new \DateTimeZone($context->user->get('timezone', $this->getCfg('timezone')));
		date_default_timezone_set($timezone->getName());

        //Route the request
        $this->route();
    }

    /**
     * Route the request
     *
     * @param Library\CommandContext $context	A command context object
     */
    protected function _actionRoute(Library\CommandContext $context)
    {
        $url = clone $context->request->getUrl();

        //Parse the route
        $this->getRouter()->parse($url);

        //Set the request
        $context->request->query->add($url->query);

        //Forward the request
        $extension = substr( $context->request->query->get('option', 'cmd', 'com_dashboard'), 4);
        $this->forward($extension);

        //Dispatch the request
        $this->dispatch();
    }

    /**
     * Dispatch the controller
     *
     * @param Library\CommandContext $context	A command context object
     */
    protected function _actionDispatch(Library\CommandContext $context)
    {
        //Render the page
        if(!$context->response->isRedirect() && $context->request->getFormat() == 'html')
        {
            //Render the page
            $config = array('response' => $context->response);

            $layout = $context->request->query->get('tmpl', 'cmd', 'default');
            $this->getObject('com:application.controller.page', $config)
                ->layout($layout)
                ->render();
        }

        parent::_actionDispatch($context);
    }

    /**
     * Render an exception
     *
     * @throws InvalidArgumentException If the action parameter is not an instance of Library\Exception
     * @param Library\CommandContext $context	A command context object
     */
    protected function _actionException(Library\CommandContext $context)
    {
        //Check an exception was passed
        if(!isset($context->param) && !$context->param instanceof Exception)
        {
            throw new \InvalidArgumentException(
                "Action parameter 'exception' [Library\EventException] is required"
            );
        }

        $config = array(
            'request'  => $this->getRequest(),
            'response' => $this->getResponse()
        );

        $this->getObject('com:application.controller.exception',  $config)
             ->render($context->param->getException());

        //Send the response
        $this->send($context);
    }

    /**
     * Load the configuration
     *
     * @return	void
     */
    public function loadConfig()
    {
        // Check if the site exists
        if($this->getObject('com:sites.model.sites')->getRowset()->find($this->getSite()))
        {
            //Load the application config settings
            JFactory::getConfig()->loadArray($this->getConfig()->options->toArray());

            //Load the global config settings
            require_once( $this->getConfig()->options->config_file );
            JFactory::getConfig()->loadObject(new JConfig());

            //Load the site config settings
            require_once( JPATH_SITES.'/'.$this->getSite().'/config/config.php');
            JFactory::getConfig()->loadObject(new JSiteConfig());

        }
        else throw new Library\ControllerExceptionNotFound('Site :'.$this->getSite().' not found');
    }

    /**
     * Load the user session or create a new one
     *
     * Old sessions are flushed based on the configuration value for the cookie lifetime. If an existing session,
     * then the last access time is updated. If a new session, a session id is generated and a record is created
     * in the users_sessions table.
     *
     * @return	void
     */
    public function getUser()
    {
        if(!$this->_user instanceof Library\DispatcherUserInterface)
        {
            $user    =  parent::getUser();
            $session =  $user->getSession();

            //Set Session Name
            $session->setName(md5($this->getCfg('secret').$this->getCfg('session_name')));

            //Set Session Lifetime
            $session->setLifetime($this->getCfg('lifetime', 15) * 60);

            //Set Session Handler
            $session->setHandler('database', array('table' => 'com:users.database.table.sessions'));

            //Set Session Options
            $session->setOptions(array(
                'cookie_path'   => (string) $this->getRequest()->getBaseUrl()->getPath() ?: '/',
                'cookie_secure' => $this->getCfg('force_ssl') == 2 ? true : false
            ));

            //Auto-start the session if a cookie is found
            if(!$session->isActive())
            {
                if ($this->getRequest()->cookies->has($session->getName())) {
                    $session->start();
                }
            }

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
     * Get the application languages.
     *
     * @return ApplicationDatabaseRowsetLanguages
     */
    public function loadLanguage(Library\CommandContext $context)
    {
        $languages = $this->getObject('application.languages');
        $language = null;

        // If a language was specified it has priority.
        if($iso_code = $this->getConfig()->options->language)
        {
            $result = $languages->find(array('iso_code' => $iso_code));
            if(count($result) == 1) {
                $language = $result->top();
            }
        }

        // Otherwise use user language setting.
        if(!$language && $iso_code = $context->user->get('language'))
        {
            $result = $languages->find(array('iso_code' => $iso_code));
            if(count($result) == 1) {
                $language = $result->top();
            }
        }

        // If language still not set, use the primary.
        if(!$language) {
            $language = $languages->getPrimary();
        }

        $languages->setActive($language);

        // TODO: Remove this.
        JFactory::getConfig()->setValue('config.language', $language->iso_code);
    }

    /**
     * Get the application router.
     *
     * @param  array $options 	An optional associative array of configuration options.
     * @return	\ApplicationRouter
     */
    public function getRouter(array $options = array())
    {
        $router = $this->getObject('com:application.router', $options);
        return $router;
    }

    /**
     * Gets a configuration value.
     *
     * @param	string	$name    The name of the value to get.
     * @param	mixed	$default The default value
     * @return	mixed	The user state.
     */
    public function getCfg( $name, $default = null )
    {
        return JFactory::getConfig()->getValue('config.' . $name, $default);
    }

    /**
     * Get the theme
     *
     * @return string The theme name
     */
    public function getTheme()
    {
        return $this->getConfig()->options->theme;
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
            if(!$this->getObject('com:sites.model.sites')->getRowset()->find($host))
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
                if(!$this->getObject('com:sites.model.sites')->getRowset()->find($site)) {
                    $site = 'default';
                }

            } else $site = $host;

            $this->_site = $site;
        }

        return $this->_site;
    }
}
