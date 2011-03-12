<?php
/**
* @version		$Id: view.html.php 14401 2010-01-26 14:10:00Z louis $
* @package		Joomla
* @subpackage	Media
* @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the WebLinks component
 *
 * @static
 * @package		Joomla
 * @subpackage	Media
 * @since 1.0
 */
class MediaViewImages extends JView
{
	function display($tpl = null)
	{
		global $mainframe;

		$config =& JComponentHelper::getParams('com_media');

		$app = JFactory::getApplication();
		$append = '';

		JHTML::_('script'    , 'popup-imagemanager.js',  'media/com_media/js/');
		JHTML::_('stylesheet', 'popup-imagemanager.css', 'media/com_media/css/');
		
		$fileTypes     = $config->get('upload_extensions', 'bmp,gif,jpg,png,jpeg');
        $types         = explode(',', $fileTypes);
        $displayTypes  = '';		// this is what the user sees
        $filterTypes   = '';		// this is what controls the logic
        $firstType     = true;
        
        foreach($types AS $type) 
        {
            if(!$firstType) {
		        $displayTypes .= ', ';
		        $filterTypes .= '; ';
            } else {
                $firstType = false;
            }
            $displayTypes .= '*.'.$type;
            $filterTypes .= '*.'.$type;
        }
        
        $typeString = '{ \''.JText::_('Files','true').' ('.$displayTypes.')\': \''.$filterTypes.'\' }';

        JHtml::_('behavior.uploader', 'upload-flash',
            array(
            	'onBeforeStart' => 'function(){ Uploader.setOptions({url: $(\'uploadForm\').action + \'&folder=\' + $(\'mediamanager-form\').folder.value}); }',
            	'onComplete' 	=> 'function(){ MediaManager.refreshFrame(); }',
            	'targetURL' 	=> '\\$(\'uploadForm\').action',
            	'typeFilter' 	=> $typeString,
            	'fileSizeMax'	=> (int) ($config->get('upload_maxsize',0) * 1024 * 1024),
            )
          );

		/*
		 * Display form for FTP credentials?
		 * Don't set them here, as there are other functions called before this one if there is any file write operation
		 */
		jimport('joomla.client.helper');
		$ftp = !JClientHelper::hasCredentials('ftp');

		$this->assignRef( 'session',	JFactory::getSession());
		$this->assignRef( 'config',		$config);
		$this->assignRef( 'state',		$this->get('state'));
		$this->assignRef( 'folderList',	$this->get('folderList'));
		$this->assign('require_ftp', $ftp);

		parent::display($tpl);
	}
}
