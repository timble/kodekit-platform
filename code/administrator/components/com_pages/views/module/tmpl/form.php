<?
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<script src="media://lib_koowa/js/koowa.js" />
<?= @helper('behavior.validator') ?>

<?= @template('com://admin/default.view.form.toolbar') ?>

<form action="<?= @route('id='.$module->id.'&application='.$state->application) ?>" method="post" class="-koowa-form">
	<input type="hidden" name="access" value="0" />
	<input type="hidden" name="published" value="0" />
	<input type="hidden" name="name" value="<?= $module->name ?>" />
	<input type="hidden" name="application" value="<?= $module->application ?>" />
	
	<div class="form-body">
		<div class="title">
			<input class="required" type="text" name="title" value="<?= @escape($module->title) ?>" />
		</div>
		
		<div class="form-content">
		    <fieldset class="form-horizontal">
		    	<legend><?= @text( 'Details' ); ?></legend>
				<div class="control-group">
				    <label class="control-label"><?= @text('Type') ?></label>
				    <div class="controls">
				        <?= @text(ucfirst($module->identifier->package)).' &raquo; '. @text(ucfirst($module->identifier->path[1])); ?>
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label"><?= @text('Description') ?></label>
				    <div class="controls">
				        <?= @text($module->description) ?>
				    </div>
				</div>
			</fieldset>
			
			<?= @helper('tabs.startPane') ?>
				<?= @helper('tabs.startPanel', array('id' => 'default', 'title' => 'Default Parameters')) ?>
					<?= @template('form_accordion', array('params' => $module->params)) ?>
				<?= @helper('tabs.endPanel') ?>				
				
				<? if($module->params->getNumParams('advanced')) : ?>
				<?= @helper('tabs.startPanel', array('id' => 'advanced', 'title' => 'Advanced Parameters')) ?>
				<?= @template('form_accordion', array('params' => $module->params, 'group' => 'advanced')) ?>
				<?= @helper('tabs.endPanel') ?>
				<? endif ?>
				
				<? if($module->params->getNumParams('other')) : ?>
				<?= @helper('tabs.startPanel', array('id' => 'other', 'title' => 'Other Parameters')) ?>
				<?= @template('form_accordion', array('params' => $module->params, 'group' => 'other')) ?>
				<?= @helper('tabs.endPanel') ?>
				<? endif ?>
			<?= @helper('tabs.endPane') ?>
						
			<? if($module->name == 'mod_custom') : ?>
			<fieldset>
				<legend><?= @text('Custom Output') ?></legend>
				<?= @service('com://admin/wysiwyg.controller.editor')->render(array('name' => 'text', 'data' => $module->content)) ?>
			</fieldset>
			<? endif ?>
		</div>
	</div>

	<div class="sidebar">
		<div class="scrollable">	
			<fieldset class="form-horizontal">
				<legend><?= @text('Publish') ?></legend>
				<div class="control-group">
				    <label class="control-label" for="published"><?= @text('Published') ?></label>
				    <div class="controls">
				        <input type="checkbox" name="published" value="1" <?= $module->published ? 'checked="checked"' : '' ?> />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for=""><?= @text('Position') ?></label>
				    <div class="controls">
                        <?= @helper('listbox.positions', array('name' => 'position', 'selected' => $module->position, 'application' => $state->application, 'deselect' => false)) ?>
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="access"><?= @text('Registered') ?></label>
				    <div class="controls">
				        <input type="checkbox" name="access" value="1" <?= $module->access ? 'checked="checked"' : '' ?> />
				    </div>
				</div>
			</fieldset>
		</div>
	</div>
</form>