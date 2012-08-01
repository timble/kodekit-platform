<?php
/**
* @version		$Id: application.php 14401 2010-01-26 14:10:00Z louis $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.helper');

/**
* Joomla! Application class
*
* Provide many supporting API functions
*
* @package		Joomla
* @final
*/
class JAdministrator extends JApplication
{
	/**
	 * Class constructor
	 *
	 * @access protected
	 * @param	array An optional associative array of configuration settings.
	 * Recognized key values include 'clientId' (this list is not meant to be comprehensive).
	 */
	function __construct($config = array())
	{
		$config['clientId']          = 1;
		$config['multisite']         = true;

		parent::__construct($config);

		//Set the root in the URI based on the application name
		JURI::root(null, str_replace('/'.$this->getName(), '', JURI::base(true)));
	}

	/**
	 * Initialise the application.
	 *
	 * @access public
	 * @param array An optional associative array of configuration settings.
	 */
	function initialise($options = array())
	{
		// If a language was specified it has priority otherwise use user or default
		// language settings
		if (empty($options['language']))
		{
			$user = & JFactory::getUser();
			$lang	= $user->getParam( 'admin_language' );

			// Make sure that the user's language exists
			if ( $lang && JLanguage::exists($lang) ) {
				$options['language'] = $lang;
			}
			else
			{
				$params = JComponentHelper::getParams('com_extensions');
				$client	=& JApplicationHelper::getClientInfo($this->getClientId());
				$options['language'] = $params->get('language_'.$client->name, 'en-GB');
			}
		}

		// One last check to make sure we have something
		if ( ! JLanguage::exists($options['language']) ) {
			$options['language'] = 'en-GB';
		}

		parent::initialise($options);
	}

	/**
	 * Route the application
	 *
	 * @param	object A JURI object.
	 * @access public
	 */
	function route($uri = null)
	{
        $url = clone KRequest::url();

        //Parse the route
        $this->getRouter()->parse($url);

        //Set the request
        JRequest::set($url->query, 'get', false );
	}

	/**
	 * Return a reference to the JRouter object.
	 *
	 * @access	public
	 * @return	JRouter.
	 * @since	1.5
	 */
	function getRouter(array $options = array())
	{
        $router = KService::get('com://admin/application.router', $options);
        return $router;
	}

	/**
	 * Dispatch the application
	 *
	 * @access public
	 * @throws KDispatcherException	If the user is not logged in.
	 */
	function dispatch()
	{ 
	    $document = JFactory::getDocument();
	    
	    switch($document->getType())
        {
            case 'html' :
            {
                $document->addScript( JURI::root(true).'/media/system/js/legacy.js');
                JHTML::_('behavior.mootools');
            }
        }

	    if(!JFactory::getUser()->authorize('login', 'administrator')) {
	        $option = 'com_users';
	    } else {
	        $option = strtolower(JRequest::getCmd('option', 'com_dashboard'));
	    }
	    
	    $document->setTitle(htmlspecialchars_decode($this->getCfg('sitename' )). ' - ' .JText::_( 'Administration' ));
	 
        JRequest::setVar('option', $option);
        $contents = JComponentHelper::renderComponent($option);   
        $document->setBuffer($contents, 'component');
	}

	/**
	 * Display the application.
	 *
	 * @access public
	 */
	function render()
	{
		$component	= JRequest::getCmd('option');
		$template	= $this->getTemplate();
		$file 		= JRequest::getCmd('tmpl', 'index');

		$params = array(
			'template' 	=> $template,
			'file'		=> $file.'.php',
			'directory'	=> JPATH_THEMES
		);

		//Render the document
		$data = JFactory::getDocument()->render($this->getCfg('caching'), $params );

		//Make images paths absolute
		$site = $this->getSite();
		$data = str_replace(array('../images', './images'), JURI::root(true).'/'.str_replace(JPATH_ROOT.DS, '', JPATH_IMAGES), $data);

		JResponse::setBody($data);
	}

	/**
	 * Get the template
	 *
	 * @return string The template name
	 * @since 1.0
	 */
	function getTemplate()
	{
		$template = 'default';

		return $template;
	}

	/**
	 * Load the user session or create a new one
	 *
	 * @param	string	The sessions name.
	 * @return	object	JSession on success. May call exit() on database error.
	 * @since	Nooku Server 0.7
	 */
    protected function _loadSession( $name, $ssl = false, $auto_start = true )
	{
		if($this->getCfg('force_ssl') >= 1) {
			$ssl = true;
		}

		return parent::_loadSession($name, $ssl, $auto_start);
	}

	/**
	 * Load the site
	 *
	 * This function checks if the site exists in the url, request, or in the session. If not it
	 * falls back on the default site.
	 *
	 * @param	string	$site 	The name of the site to load
	 * @return	void
	 * @throws  KException 	If the site could not be found
	 * @since	Nooku Server 0.7
	 */
	protected function _loadSite($site)
	{
	    if(!$site)
	    {
	         // Check URL host
	        $uri  =	clone(JURI::getInstance());
	        $host = $uri->getHost();
	    
	        if(KService::get('com://admin/sites.model.sites')->getList()->find($host)) {
	            return parent::_loadSite($host);
	        }   

	        // Check request
	        $method = strtolower(KRequest::method());
	         
	        if(KRequest::has($method.'.site')) 
	        {
	            $request = KRequest::get($method.'.site', 'cmd');
	            return parent::_loadSite($request);
	        }

	        //Use session or revert to default
	        return parent::_loadSite(JFactory::getSession()->get('site', 'default'));
	    }

	    return parent::_loadSite($site);
	}
}