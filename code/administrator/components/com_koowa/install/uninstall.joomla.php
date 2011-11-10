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

// Delete the plugin from the database
$database->setQuery("DELETE FROM `#__extensions` WHERE type = 'plugin' AND folder = 'system' AND element = 'koowa'");
$database->query();