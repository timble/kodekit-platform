<? /** $Id: default.php 299 2009-10-24 00:19:50Z johan $ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? @script(@$mediaurl.'/com_terms/js/terms.js') ?>
<? @style(@$mediaurl.'/com_terms/css/default.css') ?>

<div id="terms-panel">
	<div class="list">
		<? foreach (@$terms as $term) : ?>
		<div class="term">
			<span><?= $term->name; ?></span>
			<a title="<?= @text('Delete this tag ?') ?>" class="button-delete" rel="<?= http_build_query($term->getData(), '', '&amp;') ?>" onclick="Terms.execute('delete', this.rel)" href="#"><span>[x]</span></a/>
		</div>
		<? endforeach; ?>
	</div>
	<form action="<?= @route(); ?>" method="post">
		<input type="hidden" name="row_id"     value="<?= @$state->row_id?>" />
		<input type="hidden" name="table_name" value="<?= @$state->table_name?>" />
		<input name="name" type="text" value="" />
		<input class="button" type="submit" value="<?= @text('Add') ?>"/>
	</form>
	<?= @text('Seperate tags with commas'); ?>
</div>