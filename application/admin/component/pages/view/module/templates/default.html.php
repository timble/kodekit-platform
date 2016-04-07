<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<?= helper('behavior.koowa'); ?>
<?= helper('behavior.validator') ?>

<ktml:block prepend="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:block>

<form action="<?= route('id='.$module->id.'&application='.parameters()->application) ?>" method="post" class="-koowa-form">
	<input type="hidden" name="access" value="0" />
	<input type="hidden" name="published" value="0" />
	<input type="hidden" name="name" value="<?= $module->name ?>" />
	<input type="hidden" name="application" value="<?= $module->application ?>" />

	<div class="main">
		<div class="title">
			<input class="required" type="text" name="title" value="<?= escape($module->title) ?>" />
		</div>

		<div class="scrollable">
		    <fieldset>
		    	<legend><?= translate('Details'); ?></legend>
				<div>
				    <label><?= translate('Type') ?></label>
				    <div>
				        <?= translate(ucfirst($module->identifier->package)).' &raquo; '. translate(ucfirst($module->identifier->path[1])); ?>
				    </div>
				</div>
				<div>
				    <label><?= translate('Description') ?></label>
				    <div>
				        <?= translate($module->description) ?>
				    </div>
				</div>
			</fieldset>

            <? if($html = $params->render('params')) : ?>
            <fieldset>
				<legend><?= translate('Parameters'); ?></legend>
                <?= $html; ?>
			</fieldset>
            <? endif ?>
		</div>
	</div>

	<div class="sidebar">
        <?= import('default_sidebar.html'); ?>
	</div>
</form>