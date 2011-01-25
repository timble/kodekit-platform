<?php
/**
 * @version		$Id: view.php 14401 2010-01-26 14:10:00Z louis $
 * @package		Joomla
 * @subpackage	Installation
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

/**
 * @package		Joomla
 * @subpackage	Installation
 */

jimport('joomla.application.component.view');

class JInstallationView extends JView
{
	var $_name = 'install';
	
	/**
	 * The installation steps
	 *
	 * @var		array
	 * @access	protected
	 * @since	1.5
	 */
	var $_steps		= null;

	/**
	 * The templabe object
	 *
	 * @var		object
	 * @access	protected
	 * @since	1.5
	 */
	var $_template		= null;

	/**
	 * Language page
	 *
	 * @return	boolean True if successful
	 * @access	public
	 * @since	1.5
	 */
	function chooseLanguage()
	{
		$steps	=& $this->getSteps();
		$steps['lang'] = 'on';

		$model	=& $this->getModel();
		$lists	=& $model->getData('lists');

		$this->assign('languages', $lists['langs']);

		$this->assign('steps', $steps);
		$this->assign('page', 'language');
		$this->display();
	}

	/**
	 * Create a template object
	 *
	 * @return	boolean True if successful
	 * @access	private
	 * @since	1.5
	 */
	function _createTemplate( $bodyHtml = null, $mainHtml = 'page.html' )
	{

		jimport('joomla.template.template');

		$this->_template = new JTemplate();
		$this->_template->applyInputFilter('ShortModifiers');

		// load the wrapper and common templates
		$this->_template->setRoot( JPATH_BASE . DS . 'template' . DS. 'tmpl' );
		$this->_template->readTemplatesFromFile( $mainHtml );

		if ($bodyHtml) {
			$this->_template->setAttribute( 'body', 'src', $bodyHtml );
		}
	}

	/**
	 * The DB Config page
	 *
	 * @return	boolean True if successful
	 * @access	public
	 * @since	1.5
	 */
	function dbConfig()
	{
		$steps	=& $this->getSteps();
		$steps['dbconfig'] = 'on';
		
		$model	=& $this->getModel();
		$lists	=& $model->getData('lists');
		
		$this->assign('dbtype_options', $lists['dbTypes']);
		
		$this->assign('steps', $steps);
		$this->assign('page', 'dbconfig');
		$this->display();
	}

	/**
	 * Display the template
	 *
	 * @return	boolean True if successful
	 * @access	public
	 * @since	1.5
	 */
	function display($tpl = null)
	{
		$model	=& $this->getModel();
		$lang	=& JFactory::getLanguage();
		$vars	=& $model->getVars();

		$this->assign('direction', $lang->isRTL() ? 'rtl' : 'ltr');
		$this->assign('lang', $lang->getTag());
		$this->assign($vars);
		
		$this->setLayout('page');
		
		parent::display($tpl);
	}

	/**
	 * Report an error to the user
	 *
	 * @return	boolean True if successful
	 * @access	public
	 * @since	1.5
	 */
	function error()
	{
		$steps	=& $this->getSteps();
		$model	=& $this->getModel();
		$vars	=& $model->getVars();
		
		$msg	= $model->getError();
		$back	= $model->getData('back');
		$xmsg	= $model->getData('errors');
		
		$this->assign('message', $msg);
		$this->assign('back', $back);
		$this->assign($vars);
		
		if ($xmsg) {
			$this->assign('xmessage', $xmsg);
		}
		
		$this->assign('steps', $steps);
		$this->assign('page', 'error');
		$this->display();
	}

	/**
	 * The the final page
	 *
	 * @return	boolean True if successful
	 * @access	public
	 * @since	1.5
	 */
	function finish()
	{
		$steps	=& $this->getSteps();
		$steps['finish'] = 'on';
		
		$model	=& $this->getModel();
		$vars	=& $model->getVars();
		$buffer	= $model->getData('buffer');
		
		$this->assign($vars);
		
		if ($buffer) {
			$this->assign('buffer', $buffer);
		}
		
		$this->assign('steps', $steps);
		$this->assign('page', 'finish');
		$this->display();
	}

	/**
	 * Show the FTP config page
	 *
	 * @return	boolean True if successful
	 * @access	public
	 * @since	1.5
	 */
	function ftpConfig()
	{
		$steps	=& $this->getSteps();
		$steps['ftpconfig'] = 'on';
		
		$this->assign('steps', $steps);
		$this->assign('page', 'ftpconfig');
		$this->display();
	}

	/**
	 * Get the installation steps
	 *
	 * @return	array
	 * @access	protected
	 * @since	1.5
	 */
	function & getSteps()
	{
		if ( is_null($this->_steps) )
		{
			$this->_steps = array(
				'lang' => 'off',
				'preinstall' => 'off',
				'dbconfig' => 'off',
				'ftpconfig' => 'off',
				'mainconfig' => 'off',
				'finish' => 'off'
			);
		}

		return $this->_steps;
	}

	/**
	 * Get the template object
	 *
	 * @param	string The name of the body html file
	 * @return	patTemplate
	 * @access	protected
	 * @since	1.5
	 */
	function & getTemplate( $bodyHtml = null )
	{
		static $current;

		$change	= false;

		// Record the current template body
		if ( is_null($current) && $bodyHtml)
		{
			$current	= $bodyHtml;
			$change		= true;
		}

		// Check if we need to create the body, possibly anew
		if ( is_null( $this->_template) || $change )
		{
			$this->_createTemplate($bodyHtml);
		}

		return $this->_template;
	}
	
	/**
	 * The main configuration page
	 *
	 * @return	boolean True if successful
	 * @access	public
	 * @since	1.5
	 */
	function mainConfig()
	{
		$steps	=& $this->getSteps();
		$steps['mainconfig'] = 'on';
		
		$encodings = array('iso-8859-1','iso-8859-2','iso-8859-3','iso-8859-4','iso-8859-5','iso-8859-6','iso-8859-7','iso-8859-8','iso-8859-9','iso-8859-10','iso-8859-13','iso-8859-14','iso-8859-15','cp874','windows-1250','windows-1251','windows-1252','windows-1253','windows-1254','windows-1255','windows-1256','windows-1257','windows-1258','utf-8','big5','euc-jp','euc-kr','euc-tw','iso-2022-cn','iso-2022-jp-2','iso-2022-jp','iso-2022-kr','iso-10646-ucs-2','iso-10646-ucs-4','koi8-r','koi8-ru','ucs2-internal','ucs4-internal','unicode-1-1-utf-7','us-ascii','utf-16');
		
		$max_upload_size = min(JInstallationHelper::let_to_num(ini_get('post_max_size')), JInstallationHelper::let_to_num(ini_get('upload_max_filesize')));
		
		$this->assign('encoding_options', $encodings);
		$this->assign('maxupload', JText::sprintf('UPLOADFILESIZE',(number_format($max_upload_size/(1024*1024), 2))."MB."));
		
		$this->assign('steps', $steps);
		$this->assign('page', 'mainconfig');
		$this->display();
	}

	/**
	 * The the pre-install info page
	 *
	 * @return	boolean True if successful
	 * @access	public
	 * @since	1.5
	 */
	function preInstall()
	{
		$steps	=& $this->getSteps();
		$steps['preinstall'] = 'on';
		
		$model	=& $this->getModel();
		$lists	=& $model->getData('lists');

		$version	= new JVersion();

		$this->assign('version', $version->getLongVersion());
		$this->assign('php_options', $lists['phpOptions']);
		$this->assign('php_settings', $lists['phpSettings']);

		$this->assign('page', 'preinstall');
		$this->assign('steps', $steps);
		$this->display();
	}

	/**
	 * Remove directory messages
	 *
	 * @return	Boolean True if successful
	 * @access	public
	 * @since	1.5
	 */
	function removedir()
	{
		$this->assign('page', 'removedir');
		$this->display();
	}


	function migrateScreen() 
	{
		$steps	=& $this->getSteps();
		$model	=& $this->getModel();

		$tmpl		=& $this->getTemplate( 'migration.html' );
		$scriptpath =& $model->getData('scriptpath');
		$tmpl->addVars( 'stepbar', 	$steps, 	'step_' );
		$tmpl->addVar( 'migration', 'migration', JRequest::getVar( 'migration', 0, 'post', 'bool' ));
		$tmpl->addVar( 'buttons', 'previous', 'mainconfig');
		return $this->display();
	}
}