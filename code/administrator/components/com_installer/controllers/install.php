<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Install Controller Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 */
class ComInstallerControllerInstall extends ComDefaultControllerResource
{
    /**
	 * Constructor
	 *
	 * @param  object  An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

	    $this->registerCallback('after.post', array($this, 'cleanCache'));
	    $this->registerCallback('before.get', array($this, 'redirectIfDispatched'));
	}
	
	/**
	 * If this controller is executing get by the dispatcher, redirect to the default view
	 *
	 * @return void
	 */
	public function redirectIfDispatched(KCommandContext $context)
	{
	    if($this->isDispatched()) {
	        JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_installer&view=components'));
	    }
	}

    /**
     * Cleans mod_menu cache after successful installs
     *
     * @return  void
     */
    public function cleanCache(KCommandContext $context)
    {
        $cache = JFactory::getCache('mod_menu');
        $cache->clean();
    }

    /**
     * Set the default view
     *
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'view' => 'install'
        ));

        parent::_initialize($config);
    }

    /**
     * Post action
     * 
     * This action installs packages and displays a custom message in one of 3 ways:
     *     1. Upload, move and unarchive a package before installing it.
     *     2. Check an inputted directory for an installable package, if found then install it
     *     3. Download a package from an inputted URL, unarchive and install.
     *
     * @param   KCommandContext     A command context object
     * @return  @TODO
     */
    protected function _actionPost(KCommandContext $context)
    {
        if(KRequest::has('files.package') && KRequest::get('files.package.size', 'int') > 0)
        {
            $package = $this->_getPackageFromUpload();
        }
        elseif(KRequest::has('post.url') && KRequest::get('post.url', 'url') != 'http://')
        {
            $package = $this->_getPackageFromUrl();
        }
        elseif(KRequest::has('post.directory') && KRequest::get('post.directory', 'path'))
        {
            $package = $this->_getPackageFromFolder();
        }
        else
        {
            $this->message('No Install Type Found');
            return $this->execute('get', $context);
        }

		// Was the package unpacked?
		if (!$package) 
		{
			$this->message('Unable to find install package');
			return $this->execute('get', $context);
		}

		// Get an installer instance
		$installer = JInstaller::getInstance();

		// Install the package
		if (!$installer->install($package['dir'])) 
		{
			// There was an error installing the package
			$msg = JText::sprintf('INSTALLEXT', JText::_($package['type']), JText::_('Error'));
			$result = false;
		} 
		else 
		{
			// Package installed sucessfully
			$msg = JText::sprintf('INSTALLEXT', JText::_($package['type']), JText::_('Success'));
			$result = true;
		}

		// Set some model state values
		KFactory::get('joomla:application')->enqueueMessage($msg);
		$this->name($installer->get('name'));
		$this->message($installer->message);
		$this->extension_message($installer->get('extension.message'));

		// Cleanup the install files
		if (!is_file($package['packagefile'])) 
		{
			$config = JFactory::getConfig();
			$package['packagefile'] = $config->getValue('config.tmp_path').DS.$package['packagefile'];
		}

		JInstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);

		return $this->execute('get', $context);
	}

	protected function _getPackageFromUpload()
	{
		// Get the uploaded file information
		$userfile = JRequest::getVar('package', null, 'files', 'array' );

		// Make sure that file uploads are enabled in php
		if (!(bool) ini_get('file_uploads')) {
			JError::raiseWarning(0, JText::_('WARNINSTALLFILE'));
			return false;
		}

		// Make sure that zlib is loaded so that the package can be unpacked
		if (!extension_loaded('zlib')) {
			JError::raiseWarning(0, JText::_('WARNINSTALLZLIB'));
			return false;
		}

		// If there is no uploaded file, we have a problem...
		if (!is_array($userfile) ) {
			JError::raiseWarning(0, JText::_('No file selected'));
			return false;
		}

		// Check if there was a problem uploading the file.
		if ( $userfile['error'] || $userfile['size'] < 1 )
		{
			JError::raiseWarning(0, JText::_('WARNINSTALLUPLOADERROR'));
			return false;
		}

		// Build the appropriate paths
		$config   = JFactory::getConfig();
		$tmp_dest = $config->getValue('config.tmp_path').DS.$userfile['name'];
		$tmp_src  = $userfile['tmp_name'];

		// Move uploaded file
		jimport('joomla.filesystem.file');
		$uploaded = JFile::upload($tmp_src, $tmp_dest);

		// Unpack the downloaded package file
		$package = JInstallerHelper::unpack($tmp_dest);

		return $package;
	}

	protected function _getPackageFromFolder()
	{
		// @TODO find a better way to filter the data
		$directory = KRequest::get('post.directory', 'path', $this->getModel()->getState()->directory);

		// Detect the package type
		$type = JInstallerHelper::detectType($directory);

		// Did you give us a valid package?
		if (!$type)
		{
			JError::raiseWarning(0, JText::_('Path does not have a valid package'));
			return false;
		}

		return array(
		    'packagefile' => null,
		    'extractdir'  => null,
		    'dir'         => $directory,
		    'type'        => $type
		);
	}

	protected function _getPackageFromUrl()
	{
		// Get the URL of the package to install
		$url = JRequest::getString('url');

		// Download the package at the URL given
		$file = JInstallerHelper::downloadPackage($url);

		// Was the package downloaded?
		if (!$file) {
			JError::raiseWarning(0, JText::_('Invalid URL'));
			return false;
		}

		$config   = JFactory::getConfig();
		$tmp_dest = $config->getValue('config.tmp_path');

		// Unpack the downloaded package file
		$package = JInstallerHelper::unpack($tmp_dest.DS.$file);

		return $package;
	}
}