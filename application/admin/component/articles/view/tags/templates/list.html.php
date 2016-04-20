<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<ktml:script src="assets://articles/js/tags.js" />
<ktml:style src="assets://articles/css/tags-list.css" />

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
	<form action="<?= route('row='.parameters()->row.'&table='.parameters()->table.'&tmpl='); ?>" method="post">
		<input type="hidden" name="row"     value="<?= parameters()->row?>" />
		<input type="hidden" name="table" value="<?= parameters()->table?>" />
		<input name="title" type="text" value="" placeholder="<?= translate('Add new tag') ?>" <?= $disabled ?> />
		<input class="button" type="submit" <?= $disabled ?> value="<?= translate('Add') ?>"/>
	</form>
	<?= translate('Seperate tags with commas'); ?>
</div>