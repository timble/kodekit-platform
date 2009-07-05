<? /** $Id$ */ ?>
<? @style(@$mediaurl.'/com_beer/css/default.css'); ?>

<h1 class="componentheading"><?= @$department->title; ?></h1>
<div id="beer_info">
	<span class="people"><?= @$department->people; ?></span>
</div>
<div id="beer_desc">
	<h2><?= @text('Information'); ?></h2>
	<?= @$department->description; ?>
</div>
