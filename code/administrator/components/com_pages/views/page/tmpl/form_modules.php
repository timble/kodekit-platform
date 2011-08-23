<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>
<?= @helper('behavior.modal'); ?>

<? $modules_available	= KFactory::tmp('admin::com.extensions.model.modules')->limit(0)->getList() ?>
<? $modules_assigned	= KFactory::get('admin::com.pages.model.pages')->getAssignedModules() ?>

<?= @helper('tabs.startPanel', array('title' => 'Modules')) ?>
<section>
	<fieldset>
		<? foreach($modules_available as $module) : ?>
			<input type="checkbox" <?= in_array($module->id, $modules_assigned) ? 'checked="checked"' : '' ?> />
			<a class="modal" href="<?= @route('option=com_extensions&view=module&id='.$module->id.'&layout=modal&tmpl=modal'); ?>" rel="{handler: 'iframe', size: {x: 800, y: 400}}"><label><?= $module->title ?></label></a><br />
		<? endforeach ?>
	</fieldset>
</section>
<?= @helper('tabs.endPanel') ?>