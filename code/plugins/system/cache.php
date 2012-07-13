<?php
/**
 * @version     $Id: default.php 2776 2011-01-01 17:08:00Z johanjanssens $
 * @package     Nooku_Plugins
 * @subpackage  Koowa
 * @copyright  	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */


/**
 * System Page Cache Plugin
 *
 * @author		Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @package     Nooku_Plugins
 * @subpackage  System
 */
class  plgSystemCache extends PlgKoowaDefault
{
	var $_cache = null;

	/**
	 * Constructor
	 *
	 * @param	object	$subject The object to observe
	 * @param 	array   $config  An array that holds the plugin configuration
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		//Set the language in the class
		$config  = JFactory::getConfig();
		$options = array(
			'cachebase' 	=> JPATH_CACHE,
			'defaultgroup' 	=> 'page',
			'lifetime' 		=> $this->params->get('cachetime', 15) * 60,
			'browsercache'	=> $this->params->get('browsercache', false),
			'caching'		=> false,
			'language'		=> $config->getValue('config.language', 'en-GB')
		);

		jimport('joomla.cache.cache');
		$this->_cache =& JCache::getInstance( 'page', $options );
	}

	/**
	* Converting the site URL to fit to the HTTP request
	*/
	public function onBeforeControllerRoute(KEvent $event)
	{
		if(JFactory::getApplication()->isAdmin() || JDEBUG) {
			return;
		}

        if (!JFactory::getUser()->get('aid') && $_SERVER['REQUEST_METHOD'] == 'GET') {
			$this->_cache->setCaching(true);
		}

		$data  = $this->_cache->get();

		if($data !== false)
		{
			// the following code searches for a token in the cached page and replaces it with the
			// proper token.
			$token	= JUtility::getToken();
			$search = '#<input type="hidden" name="[0-9a-f]{32}" value="1" />#';
			$replacement = '<input type="hidden" name="'.$token.'" value="1" />';
			$data = preg_replace( $search, $replacement, $data );

			JResponse::setBody($data);

			echo JResponse::toString(JFactory::getApplication()->getCfg('gzip'));
			exit(0);
		}
	}

	public function onAfterControllerRender(KEvent $event)
	{
		if(JFactory::getApplication()->isAdmin() || JDEBUG) {
			return;
		}

        //We need to check again here, because auto-login plugins have not been fired before the first aid check
		if(!JFactory::getUser()->get('aid')) {
			$this->_cache->store();
		}
	}
}
