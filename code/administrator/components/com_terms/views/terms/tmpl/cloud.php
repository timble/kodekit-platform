<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<style src="media://com_terms/css/terms-cloud.css" />

<ul id="terms-terms-cloud">
	<? foreach ($terms as $term) : ?>
	<? list($component, $view) = explode("_", $term->table) ?>
	<li>
		<span><?= $term->count ?> <?= @text('items are tagged with') ?></span>
		<a  href="<?= @route('option=com_'.$component.'&view='.$view.'&tag='.$term->slug) ?>" class="weight<?= round($term->count/$total + 1) ?>" ><?= $term->title; ?></a>
	</li>
	<? endforeach; ?>
	<li>
		<a href="<?= @route('option=com_'.$component.'&view='.$view.'&tag=') ?>"><?= @text('All tags') ?></a>
	</li>
</ul>