<?php
/**
 * @version     $Id$
 * @category    Koowa
 * @package     Koowa_Components
 * @subpackage  Koowa
 * @copyright   Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Prevent the plugin row to be inserted more than once
$query = "SELECT COUNT(*) FROM `#__plugins` WHERE element = 'koowa' AND folder = 'system'";
$database->setQuery($query);
if(!$database->loadResult())
{
    // Insert in database
    $plugin = JTable::getInstance('plugin');
    $plugin->name = 'System - Koowa';
    $plugin->folder = 'system';
    $plugin->element = 'koowa';
    $plugin->published = 1;
    if (!$plugin->store()) {
        // Install failed, roll back changes
        $this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$database->stderr(true));
        return false;
    }
}