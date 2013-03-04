<?php
/**
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Terms
 * @copyright   Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

<script src="media://terms/js/terms.js" />
<style src="media://terms/css/default.css" />

<? $disabled = $disabled ? 'disabled="disabled"' : ''; ?>

<div id="terms-list">
	<div class="list">
		<? foreach (@$terms as $term) : ?>
		<div class="term">
			<span><?= $term->title; ?></span>
			<a title="<?= @text('Delete this tag ?') ?>" data-action="delete" data-id="<?= $term->id; ?>" href="#"><span>[x]</span></a>
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