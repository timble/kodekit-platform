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

<style src="media://com_koowa/css/default.css" />
<style src="media://com_koowa/css/form.css" />
<script src="media://com_koowa/js/Fx.Toggle.js" />
<script src="media://com_koowa/js/Widget.js" />
<script src="media://com_pages/js/page.js" />

<style src="media://com_pages/css/page-form.css" />

<script>
	function checksubmit(form)
	{
		var submitOK=true;
		var checkaction=form.action.value;
		// do field validation
		if (checkaction=='cancel') {
			return true;
		}
		if (form.title.value == ""){
			alert( "<?= @text( 'Page must have a title', true ); ?>" );
			submitOK=false;
			// remove the action field to allow another submit
			form.action.remove();
		}
		return submitOK;
	}

	window.addEvent('domready', function(){
		$$('.toggle-select select').toggle(<?= json_encode(array('lang' => array('edit' => @text('Edit'), 'ok' => @text('OK'), 'cancel' => @text('Cancel')))) ?>);
		$$('.toggle-select input').toggle(<?= json_encode(array('lang' => array('edit' => @text('Edit'), 'ok' => @text('OK'), 'cancel' => @text('Cancel')))) ?>);
		$$('.widget').widget({cookie: 'widgets-page'});

		new Page(<?= json_encode(array(
			'active' => '[name=type_option]'
		)) ?>);
	});
</script>

<form action="<?= @route('&id='.$page->id) ?>" method="post" name="adminForm" class="-koowa-box-horizontal -koowa-box-flex">
	<input type="hidden" name="menu" value="<?= $state->menu ?>" />
	<input type="hidden" name="type" value="<?= $state->type['name'] ?>" />

	<? if($state->type && $state->type['name'] == 'component') : ?>
		<input type="hidden" name="type_option" value="<?= $state->type['option'] ?>" />
		<input type="hidden" name="type_view" value="<?= $state->type['view'] ?>" />
		<input type="hidden" name="type_layout" value="<?= $state->type['layout'] ?>" />
	<? endif ?>

	<?= @template('form_types') ?>

	<? if($state->type) : ?>
	<div id="main" class="-koowa-box-vertical -koowa-box-flex">
		<fieldset id="title">
			<label for="title">
				<span><?= @text('Page Title') ?>:</span>
				<input type="text" name="title" placeholder="<?= @text('Title') ?>" value="<?= $page->title ?>" size="50" maxlength="255" />
			</label><br />
			<label for="alias" class="toggle-select">
				<?= @text('Visitors can access this page at'); ?>
				 <?= $live_site ?>/<input type="text" name="slug" placeholder="<?= @text('Alias') ?>" value="<?= $page->slug; ?>" maxlength="255" />
			</label>
		</fieldset>
		<? if(KRequest::get('get.type', 'cmd')) : ?>
			<?=	@helper('tabs.startPane', array('id' => 'pane_1')); ?>
			<?= @helper('tabs.startPanel', array('title' => 'General')); ?>
				<?= @template('form_general') ?>
			<?= @helper('tabs.endPanel'); ?>
			<? if($state->type['name'] == 'component') : ?>
				<?= @template('form_component') ?>
				<?= @template('form_system') ?>
			<? endif ?>
			<?= @template('form_modules') ?>
			<?= @helper('tabs.endPane'); ?>
		<? endif ?>
	</div>
	<? endif ?>
</form>