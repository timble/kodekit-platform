<?
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<script src="media://js/koowa.js" />
<?= @helper('behavior.validator') ?>

<ktml:module position="toolbar">
    <?= @helper('toolbar.render', array('toolbar' => $toolbar))?>
</ktml:module>

<form action="<?= @route('id='.$module->id.'&application='.$state->application) ?>" method="post" class="-koowa-form">
	<input type="hidden" name="access" value="0" />
	<input type="hidden" name="published" value="0" />
	<input type="hidden" name="name" value="<?= $module->name ?>" />
	<input type="hidden" name="application" value="<?= $module->application ?>" />
	
	<div class="main">
		<div class="title">
			<input class="required" type="text" name="title" value="<?= @escape($module->title) ?>" />
		</div>

		<div class="scrollable">
		    <fieldset>
		    	<legend><?= @text( 'Details' ); ?></legend>
				<div>
				    <label><?= @text('Type') ?></label>
				    <div>
				        <?= @text(ucfirst($module->identifier->package)).' &raquo; '. @text(ucfirst($module->identifier->path[1])); ?>
				    </div>
				</div>
				<div>
				    <label><?= @text('Description') ?></label>
				    <div>
				        <?= @text($module->description) ?>
				    </div>
				</div>
			</fieldset>

            <? if($params_rendered = $params->render('params')) : ?>
            <fieldset>
				<legend><?= @text( 'Default Parameters' ); ?></legend>
                <?= $params_rendered; ?>
			</fieldset>
            <? endif ?>

            <? if($params_rendered = $params->render('params', 'advanced')) : ?>
			<fieldset>
				<legend><?= @text( 'Advanced Parameters' ); ?></legend>
                <?= $params_rendered; ?>
			</fieldset>
			<? endif ?>

            <? if($params_rendered = $params->render('params', 'other')) : ?>
			<fieldset>
				<legend><?= @text( 'Other Parameters' ); ?></legend>
                <?= $params_rendered; ?>
			</fieldset>
			<? endif ?>

			<? if($module->name == 'mod_custom') : ?>
			<fieldset>
				<legend><?= @text('Custom Output') ?></legend>
				<?= @object('com:wysiwyg.controller.editor')->render(array('name' => 'content', 'text' => $module->content)) ?>
			</fieldset>
			<? endif ?>
		</div>
	</div>

	<div class="sidebar">
        <?= @template('default_sidebar.html'); ?>
	</div>
</form>