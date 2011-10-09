<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); 
?>

<div id="cpanel">
<?= @helper('image.icon', array(
		'link'  => 'option=com_articles&view=article',
		'text'  => 'Add New Article',
        'image' => 'icon-48-article-add'
    )); 
?>

<?= @helper('image.icon', array(
		'link'  => 'option=com_articles',
		'text'  => 'Article Manager',
        'image' => 'icon-48-article'
    )); 
?>

<?= @helper('image.icon', array(
		'link'  => 'option=com_articles&view=sections',
		'text'  => 'Section Manager',
        'image' => 'icon-48-section'
    )); 
?>
	
<?= @helper('image.icon', array(
		'link'  => 'option=com_articles&view=categories',
		'text'  => 'Category Manager',
        'image' => 'icon-48-category'
    )); 
?>
	
<?= @helper('image.icon', array(
		'link'  => 'option=com_files',
		'text'  => 'File Manager',
        'image' => 'icon-48-files'
    )); 
?>

<? if ( JFactory::getUser()->get('gid') > 23 )  : ?>	
<?= @helper('image.icon', array(
		'link'  => 'option=com_menus',
		'text'  => 'Menu Manager',
        'image' => 'icon-48-menumgr'
    )); 
?>
<? endif; ?>

<? if ( JFactory::getUser()->get('gid') > 24 )  : ?>	
<?= @helper('image.icon', array(
		'link'  => 'option=com_extensions&view=languages',
		'text'  => 'Language Manager',
        'image' => 'icon-48-language'
    )); 
?>
<? endif; ?>	
	
<? if ( JFactory::getUser()->get('gid') > 23 )  : ?>	
<?= @helper('image.icon', array(
		'link'  => 'option=com_users',
		'text'  => 'User Manager',
        'image' => 'icon-48-user'
    )); 
?>
<? endif; ?>
	
<? if ( JFactory::getUser()->get('gid') > 24 )  : ?>	
<?= @helper('image.icon', array(
		'link'  => 'option=com_settings',
		'text'  => 'Global Configuration',
        'image' => 'icon-48-config'
    )); 
?>
<? endif; ?>
</div>