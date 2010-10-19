<?php
/**
 * @version		$Id$
 * @category	Nooku_Server
 * @package     Plugins
 * @subpackage  System
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin');

class plgSystemMultisite extends JPlugin
{
	public function onAfterInitialise()
	{
		$app = JFactory::getApplication();
		
		//Define the sites folder
		define( 'JPATH_SITES',	JPATH_ROOT.DS.'sites');
		
		//Load the default router first
		$router =& $app->getRouter();
				
		//Replace default with our custom router
		require_once(dirname(__FILE__).'/multisite/'.$app->getName().'.php');
		$router = new JRouterMultisite(array('mode' => $router->getMode())); 
	}
	
	public function onAfterRoute()
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();
		
		//Perform Route
		if($app->getName() == 'administrator') {
			$app->getRouter()->parse(clone(JURI::getInstance()));
		}
		
		//Load Config
		$site = $app->getRouter()->getSite();
		
		require_once( JPATH_SITES.'/'.$site.'/settings.php');
		$config = JFactory::getConfig()->loadObject(new JSettings());	
		
		//Reset Database
		$database = JFactory::getDBO();
		$database->select($app->getCfg('db'));
		$database->setPrefix($app->getCfg('dbprefix'));	
		
		//Re-login
		if($app->getUserState('application.site') != $site && !$user->get('guest'))
		{
			// Fork the session to prevent session fixation issues
			$session = JFactory::getSession();
			$session->fork();
			
			$app->_createSession($session->getId());
			
			// Import the user plugin group
			JPluginHelper::importPlugin('user');

			$response = array(
				'username' 		=> $user->get('username'),
				'email'	   		 => $user->get('username'),
				'fullname' 		 => $user->get('fullname'),
				'password_clear' => ''
			);
			
			$options = array(
				'group' 		=> 'Public Backend',
				'autoregister' 	=> false,
			);
			
			$results = $app->triggerEvent('onLoginUser', array($response, $options));
			
			if(JError::isError($results[0])) 
			{
				$app->triggerEvent('onLoginFailure', array((array)$response));
				
				//Log the user out
				$app->logout();
			}
		}
		
		// Set session
		JFactory::getApplication()->setUserState('application.site', $site);
	}
	
	public function onAfterRender()
	{
		$app  = JFactory::getApplication();
		$site = $app->getRouter()->getSite();
		
		//Exception for the default site
		if($site == 'default') {
			$site = '';
		}
		
		if($app->getName() == 'administrator' && !empty($site)) 
		{
			$index = $app->getCfg('sef_rewrite') ? ''  : 'index.php/';
			
			$body = str_replace('index.php/'.$site, 'index.php', JResponse::getBody());
			$body = str_replace('index.php', JURI::base(true).'/'.$index.$site, $body);
			JResponse::setBody($body);
		}
	}
}