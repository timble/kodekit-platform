<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<ktml:script src="assets://tags/js/tags.js" />
<ktml:style src="assets://tags/css/tags-list.css" />

<? $disabled = $disabled ? 'disabled="disabled"' : ''; ?>

<div id="tags-list">
	<div class="list">
		<? foreach (@$tags as $tag) : ?>
		<div class="tag">
			<span><?= $tag->title; ?></span>
			<a title="<?= translate('Delete this tag ?') ?>" data-action="delete" data-id="<?= $tag->id; ?>" href="#"><span>[x]</span></a>
		</div>
		<? endforeach; ?>
	</div>
	<form action="<?= route('row='.state()->row.'&table='.state()->table.'&tmpl='); ?>" method="post">
		<input type="hidden" name="row"     value="<?= state()->row?>" />
		<input type="hidden" name="table" value="<?= state()->table?>" />
		<input name="title" type="text" value="" placeholder="<?= translate('Add new tag') ?>" <?= $disabled ?> />
		<input class="button" type="submit" <?= $disabled ?> value="<?= translate('Add') ?>"/>
	</form>
	<?= translate('Seperate tags with commas'); ?>
</div>