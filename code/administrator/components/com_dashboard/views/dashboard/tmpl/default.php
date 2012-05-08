<?
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Dashboard
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<module position="title" content="replace">
	<?= @helper('toolbar.title', array('toolbar' => $toolbar))?>
</module>

<module position="toolbar" content="replace">
	<?= @helper('toolbar.render', array('toolbar' => $toolbar))?>
</module>

<?= @overlay(array('url' => @route('option=com_activities&view=activities&layout=list'))); ?>

<div class="sidebar-right" style="width:400px; padding: 20px;">
    <? foreach ($modules as $module) : ?>
    <div class="<?= $module->type ?>">
    	<h3><?= $module->title ?></h3>
    	<?= @service('mod://admin/'.substr($module->type, 4).'.html')->module($module)->display(); ?>
    </div>
    <? endforeach ?>
</div>