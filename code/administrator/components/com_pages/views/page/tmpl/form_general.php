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

<section id="publish">
	<h3><?= @text('Publish') ?></h3>
	<fieldset>
		<div class="row toggle-select">
			<label for="status"><?= @text('Status') ?>:</label>
			<?= @helper('admin::com.pages.template.helper.listbox.published',  array('deselect' => false)) ?>
		</div>
		<div class="row toggle-select">
			<label for="publish_up"><?= @text('Visibility') ?>:</label>
			<?= @helper('admin::com.pages.template.helper.listbox.access',  array('deselect' => false)) ?>
		</div>
		<div class="row">
			<label for="parent"><?= @text('Parent') ?>:</label><br />
			<span id="parent"><?= @helper('admin::com.pages.template.helper.listbox.parents',
				array('pages_page_id' => $page->id, 'pages_menu_id' => $state->menu, 'selected' => $page->parent_id)) ?></span>
		</div>
	</fieldset>
</section>

<section>
	<h3><?= @text('Content') ?></h3>
	<fieldset>
		<? $model = KFactory::get('admin::com.pages.model.pages') ?>

		<? if($state->type['name'] == 'component') : ?>
			<? $url_parameters = $model->getUrlParameters(); ?>
			<?= $url_parameters->render('urlparams') ?>
		<? endif ?>

		<? $state_parameters = $model->getStateParameters() ?>
		<? if(count($state_parameters->getParams('params'))) : ?>
			<?= $state_parameters->render('params') ?>
		<? endif ?>

		<? if(!(count($state_parameters->getNumParams('params')) || isset($url_parameters) && count($url_parameters->getNumParams('urlparams')))) : ?>
		 	<div style="text-align: center; padding: 5px;">
		 		<?= @text('There are no parameters for this item') ?>
		 	</div>
		<? endif ?>
	</fieldset>
</section>

<? $model = KFactory::get('admin::com.pages.model.pages') ?>

<? $advanced_parameters = $model->getAdvancedParameters() ?>
<? if($rendered_parameters = $advanced_parameters->render('params')) : ?>
<section>
	<h3><?= @text('Advanced') ?></h3>
	<fieldset>
		<?= $rendered_parameters ?>
	</fieldset>
</section>
<? endif ?>