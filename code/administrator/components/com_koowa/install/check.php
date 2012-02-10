<?php
/**
 * @version     $Id$
 * @category    Koowa
 * @package     Koowa_Components
 * @subpackage  Koowa
 * @copyright   Copyright (C) 2010 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$errors = $warnings = array();
if(extension_loaded('suhosin'))
{
    //Attempt setting the whitelist value
    @ini_set('suhosin.executor.include.whitelist', 'tmpl://, file://');

    //Checking if the whitelist is ok
    if(!@ini_get('suhosin.executor.include.whitelist') || strpos(@ini_get('suhosin.executor.include.whitelist'), 'tmpl://') === false)
    {
        $errors[] = sprintf(JText::_('The install failed because your server has Suhosin loaded, but it\'s not configured correctly. Please follow <a href="%s" target="_blank">this</a> tutorial before you reinstall.'), 'https://nooku.assembla.com/wiki/show/nooku-framework/Known_Issues');
    }
}

if(!class_exists('mysqli'))
{
    $errors[] = JText::_("We're sorry but your server isn't configured with the MySQLi database driver. Please contact your host and ask them to enable MySQLi for your server.");
}

if(version_compare(phpversion(), '5.2', '<'))
{
    $errors[] = sprintf(JText::_("Nooku Framework requires PHP 5.2 or later. Your server is running PHP %s."), phpversion());
}

if(version_compare($database->getVersion(), '5.0.41', '<'))
{
    $errors[] = sprintf(JText::_("Nooku Framework requires MySQL 5.0.41 or later. Your server is running MySQL %s."), $database->getVersion());
}

//Some hosts that specialize on Joomla are known to lock permissions to the libraries folder
if(!is_writable(JPATH_LIBRARIES))
{
    $errors[] = sprintf(JText::_("The <em title=\"%s\">libraries</em> folder needs to be writable in order for Nooku Framework to install."), JPATH_LIBRARIES);
}

//If there were errors, backup the temporary files before the installation aborts, allowing the user to more easily attempt an reinstall
if($errors)
{
    echo '<h1>', JText::_("The installation can't proceed until you resolve the following:"), '</h1>';
    echo '<ul>';
    foreach($errors as $error)
    {
        echo '<li>', $error, '</li>';
    }
    echo '</ul>';
    
    JFactory::getApplication()->set('com_install', false);
    
    //@TODO backup unarchived install package in tmp folder to allow user to retry the installation quicker
}