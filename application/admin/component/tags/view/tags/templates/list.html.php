<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */
?>

<script src="media://tags/js/tags.js" />
<style src="media://tags/css/tags-list.css" />

<? $disabled = $disabled ? 'disabled="disabled"' : ''; ?>

<div id="tags-list">
	<div class="list">
		<? foreach (@$tags as $tag) : ?>
		<div class="tag">
			<span><?= $tag->title; ?></span>
			<a title="<?= @text('Delete this tag ?') ?>" data-action="delete" data-id="<?= $tag->id; ?>" href="#"><span>[x]</span></a>
		</div>
		<? endforeach; ?>
	</div>
	<form action="<?= @route('row='.@$state->row.'&table='.$state->table.'&tmpl='); ?>" method="post">
		<input type="hidden" name="row"     value="<?= $state->row?>" />
		<input type="hidden" name="table" value="<?= $state->table?>" />
		<input name="title" type="text" value="" placeholder="<?= @text('Add new tag') ?>" <?= $disabled ?> />
		<input class="button" type="submit" <?= $disabled ?> value="<?= @text('Add') ?>"/>
	</form>
	<?= @text('Seperate tags with commas'); ?>
</div>