<? /** $Id: default.php 299 2009-10-24 00:19:50Z johan $ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? @script(@$mediaurl.'/com_terms/js/terms.js') ?>
<? @style(@$mediaurl.'/com_terms/css/default.css') ?>

<? $disabled = @$disabled ? 'disabled="disabled"' : ''; ?>

<div id="terms-panel">
	<div class="list">
		<? foreach (@$terms as $term) : ?>
		<div class="term">
			<span><?= $term->name; ?></span>
			<a title="<?= @text('Delete this tag ?') ?>" class="button-delete"  onclick="Terms.execute('delete', <?= $term->terms_relation_id; ?>)" href="#"><span>[x]</span></a/>
		</div>
		<? endforeach; ?>
	</div>
	<form action="<?= @route('row_id='.@$state->row_id.'&table_name='.@$state->table_name); ?>" method="post">
		<input type="hidden" name="row_id"     value="<?= @$state->row_id?>" />
		<input type="hidden" name="table_name" value="<?= @$state->table_name?>" />
		<input name="name" type="text" value="" <?= $disabled ?> />
		<input class="button" type="submit" <?= $disabled ?> value="<?= @text('Add') ?>"/>
	</form>
	<?= @text('Seperate tags with commas'); ?>
</div>