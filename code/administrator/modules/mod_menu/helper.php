<?php
/**
* @version		$Id:mod_menu.php 2463 2006-02-18 06:05:38Z webImagery $
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
defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__).DS.'menu.php');

class modMenuHelper
{
	/**
	 * Show the menu
	 * @param string The current user type
	 */
	function buildMenu()
	{
		global $mainframe;

		$lang		= & JFactory::getLanguage();
		$user		= & JFactory::getUser();
		$db			= & JFactory::getDBO();
		$usertype	= $user->get('usertype');

		// cache some acl checks
		$canConfig			= $user->authorize('com_settings', 'manage');
		$manageTemplates	= $user->authorize('com_templates', 'manage');
		$manageMenuMan		= $user->authorize('com_menus', 'manage');
		$manageLanguages	= $user->authorize('com_languages', 'manage');
		$installModules		= $user->authorize('com_installer', 'module');
		$editAllModules		= $user->authorize('com_modules', 'manage');
		$installPlugins		= $user->authorize('com_installer', 'plugin');
		$editAllPlugins		= $user->authorize('com_plugins', 'manage');
		$installComponents	= $user->authorize('com_installer', 'component');
		$editAllComponents	= $user->authorize('com_components', 'manage');
		$canManageUsers		= $user->authorize('com_users', 'manage');

		// Menu Types
		require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_menus'.DS.'helpers'.DS.'helper.php' );
		$menuTypes 	= MenusHelper::getMenuTypelist();

		/*
		 * Get the menu object
		 */
		$menu = new JAdminCSSMenu();

		/*
		 * Site SubMenu
		 */
		$menu->addChild(new JMenuNode(JText::_('Dashboard'), 'index.php?option=com_dashboard&view=dashboard', 'class:cpanel'), true);
		$menu->getParent();
		
		/*
		 * Menus SubMenu
		 */
		if ($manageMenuMan) {
			$menu->addChild(new JMenuNode(JText::_('Menus'), 'index.php?option=com_menus&task=view', 'class:menu'));
		}

		/*
		 * Components SubMenu
		 */
		if ($editAllComponents)
		{
			$menu->addChild(new JMenuNode(JText::_('Components')), true);

			$query = 'SELECT *' .
				' FROM #__components' .
				' WHERE '.$db->NameQuote( 'option' ).' <> "com_files"' .
				' AND enabled = 1' .
				' ORDER BY ordering, name';
			$db->setQuery($query);
			$comps = $db->loadObjectList(); // component list
			$subs = array(); // sub menus
			$langs = array(); // additional language files to load

			// first pass to collect sub-menu items
			foreach ($comps as $row)
			{
				if ($row->parent)
				{
					if (!array_key_exists($row->parent, $subs)) {
						$subs[$row->parent] = array ();
					}
					$subs[$row->parent][] = $row;
					$langs[$row->option.'.menu'] = true;
				} elseif (trim($row->admin_menu_link)) {
					$langs[$row->option.'.menu'] = true;
				}
			}

			// Load additional language files
			if (array_key_exists('.menu', $langs)) {
				unset($langs['.menu']);
			}
			foreach ($langs as $lang_name => $nothing) {
				$lang->load($lang_name);
			}

			foreach ($comps as $row)
			{
				if ($editAllComponents | $user->authorize('administration', 'edit', 'components', $row->option))
				{
					if ($row->parent == 0 && (trim($row->admin_menu_link) || array_key_exists($row->id, $subs)))
					{
						$text = $lang->hasKey($row->option) ? JText::_($row->option) : $row->name;
						$link = $row->admin_menu_link ? "index.php?$row->admin_menu_link" : "index.php?option=$row->option";
						if (array_key_exists($row->id, $subs)) {
							$menu->addChild(new JMenuNode($text, $link, $row->admin_menu_img), true);
							foreach ($subs[$row->id] as $sub) {
								$key  = $row->option.'.'.$sub->name;
								$text = $lang->hasKey($key) ? JText::_($key) : $sub->name;
								$link = $sub->admin_menu_link ? "index.php?$sub->admin_menu_link" : null;
								$menu->addChild(new JMenuNode($text, $link, $sub->admin_menu_img));
							}
							$menu->getParent();
						} else {
							$menu->addChild(new JMenuNode($text, $link, $row->admin_menu_img));
						}
					}
				}
			}
			$menu->getParent();
		}
		
		/*
		 * Users SubMenu
		 */
		$menu->addChild(new JMenuNode(JText::_('Files'), 'index.php?option=com_files', 'class:files'), true);
		$menu->getParent();
		
		/*
		 * Users SubMenu
		 */
		if ($canManageUsers) {
			$menu->addChild(new JMenuNode(JText::_('Users'), 'index.php?option=com_users&view=users', 'class:user'), true);
			$menu->getParent();
		}

		/*
		 * Extensions SubMenu
		 */
		if ($installModules)
		{
			$menu->addChild(new JMenuNode(JText::_('Extensions')), true);

			$menu->addChild(new JMenuNode(JText::_('Install/Uninstall'), 'index.php?option=com_installer', 'class:install'));
			$menu->addSeparator();
			if ($editAllModules) {
				$menu->addChild(new JMenuNode(JText::_('Modules'), 'index.php?option=com_extensions&view=modules', 'class:module'));
			}
			if ($editAllPlugins) {
				$menu->addChild(new JMenuNode(JText::_('Plugins'), 'index.php?option=com_extensions&view=plugins', 'class:plugin'));
			}
			if ($manageTemplates) {
				$menu->addChild(new JMenuNode(JText::_('Templates'), 'index.php?option=com_extensions&view=templates', 'class:themes'));
			}
			if ($manageLanguages) {
				$menu->addChild(new JMenuNode(JText::_('Languages'), 'index.php?option=com_extensions&view=languages', 'class:language'));
			}
			$menu->getParent();
		}
		
		/*
		 * System SubMenu
		 */
		if ($canConfig)
		{
			$menu->addChild(new JMenuNode(JText::_('Tools')), true);
			
			if ($canConfig) {
				$menu->addChild(new JMenuNode(JText::_('Settings'), 'index.php?option=com_settings', 'class:config'));
				$menu->addChild(new JMenuNode(JText::_('System Info'), 'index.php?option=com_info&view=system', 'class:info'));
				$menu->addSeparator();
			}

			$menu->addChild(new JMenuNode(JText::_('Clean Cache'), 'index.php?option=com_cache&view=items', 'class:config'));
			$menu->getParent();
		}

		$menu->renderMenu('menu', '');
	}

	/**
	 * Show an disbaled version of the menu, used in edit pages
	 *
	 * @param string The current user type
	 */
	function buildDisabledMenu()
	{
		$lang	 =& JFactory::getLanguage();
		$user	 =& JFactory::getUser();
		$usertype = $user->get('usertype');

		$canConfig			= $user->authorize('com_settings', 'manage');
		$installModules		= $user->authorize('com_installer', 'module');
		$editAllModules		= $user->authorize('com_modules', 'manage');
		$installPlugins		= $user->authorize('com_installer', 'plugin');
		$editAllPlugins		= $user->authorize('com_plugins', 'manage');
		$installComponents	= $user->authorize('com_installer', 'component');
		$editAllComponents	= $user->authorize('com_components', 'manage');
		$canManageUsers		= $user->authorize('com_users', 'manage');

		$text = JText::_('Menu inactive for this Page', true);

		// Get the menu object
		$menu = new JAdminCSSMenu();

		// Site SubMenu
		$menu->addChild(new JMenuNode(JText::_('Dashboard'), null, 'disabled'));

		// Menus SubMenu
		$menu->addChild(new JMenuNode(JText::_('Menus'), null, 'disabled'));

		// Content SubMenu
		$menu->addChild(new JMenuNode(JText::_('Components'), null, 'disabled'));

		// Components SubMenu
		$menu->addChild(new JMenuNode(JText::_('Files'), null, 'disabled'));
		
		// Content SubMenu
		$menu->addChild(new JMenuNode(JText::_('Users'), null, 'disabled'));

		// Extensions SubMenu
		if ($installModules) {
			$menu->addChild(new JMenuNode(JText::_('Extensions'), null, 'disabled'));
		}

		// System SubMenu
		if ($canConfig) {
			$menu->addChild(new JMenuNode(JText::_('Tools'),  null, 'disabled'));
		}

		$menu->renderMenu('menu', 'disabled');
	}
}