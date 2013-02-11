<?
/**
 * @package     Nooku_Components
 * @subpackage  Articles
 * @copyright   Copyright (C) 2007 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<ktml:module position="toolbar">
	<?= @helper('toolbar.render', array('toolbar' => $toolbar))?>
</ktml:module>

<ktml:module position="menubar">
	<?= @helper('menubar.render', array('menubar' => $menubar, 'attribs' => array('id' => 'menubar')))?>
</ktml:module>