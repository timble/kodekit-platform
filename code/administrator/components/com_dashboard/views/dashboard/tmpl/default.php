<?
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Dashboard
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
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

<div class="-koowa-box-flex panel" style="margin-right: 20px;">
	<h3><?= @text('Recent Activity')?></h3>
    <?= @overlay(array('url' => @route('option=com_activities&view=activities&layout=list'))); ?>
</div>
<div style="width:550px;">
<?= @helper('accordion.startPane') ?>
    <? foreach ($modules as $module) : ?>
        <?= @helper('accordion.startPanel', array('title' => $module->title)) ?>
        <?= @service('mod://admin/'.substr($module->type, 4).'.html')->module($module)->display(); ?>
        <?= @helper('accordion.endPanel') ?>
    <? endforeach ?>
<?= @helper('accordion.endPane') ?>
</div>