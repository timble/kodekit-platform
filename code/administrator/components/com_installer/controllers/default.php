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
 * Default Controller Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 */
class ComInstallerControllerDefault extends ComDefaultControllerDefault
{
    public function getRequest()
    {
        $request = parent::getRequest();    
        $request->hidden = false;
    
    	return $request;
    }
    
    /**
     * Cannot add anything in this controller
     *
     * @return  false
     */
    public function canAdd()
    {
        return false;
    }

    /**
     * Generic uninstall function
     *
     * @param	KCommandContext	A command context object
     * @return 	KDatabaseRowset	A rowset object containing the deleted rows
     */
    protected function _actionDelete(KCommandContext $context)
	{
        $request = $this->getRequest();
        $type    = $this->getIdentifier()->name;
        
		// Initialize variables
		$failed = array ();

		// Get an installer object for the extension type
		$installer = JInstaller::getInstance();

        foreach($request->id as $composite)
        {
            $parts  = explode('-', $composite);
            $id     = $parts[0];
            $appid  = isset($parts[1]) ? $parts[1] : 0;
			$result	= $installer->uninstall($type, $id, $appid);

			// Build an array of extensions that failed to uninstall
			if ($result === false) {
				$failed[] = $id;
			}    
        }

		if (count($failed)) 
		{
			// There was an error in uninstalling the package
			$msg = JText::sprintf('UNINSTALLEXT', JText::_($this->_type), JText::_('Error'));
			$result = false;
		} 
		else 
		{
			// Package uninstalled sucessfully
			$msg = JText::sprintf('UNINSTALLEXT', JText::_($this->_type), JText::_('Success'));
			$result = true;
		}

        $controller = $this->getService('com://admin/installer.controller.install');
		$controller->name($installer->get('name'));
		$controller->message($installer->message);
		$controller->extension_message($installer->get('extension.message'));
		
		JFactory::getApplication()->enqueueMessage($msg);

		return $controller->get();
	}
}