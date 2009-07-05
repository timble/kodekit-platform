<? /** $Id$ */ ?>
<? @style(@$mediaurl.'/com_beer/css/default.css'); ?>

<h1 class="componentheading"><?= @$person->firstname . ' ' .  @$person->middlename . ' ' . @$person->lastname; ?></h1>
<div id="beer_info">
	<span class="mobile"><?= @$person->mobile; ?></spam>
	<span class="email"><?= @$office->email; ?></span>
	</div>
<div id="beer_desc">
	<h2><?= @text('Bio'); ?></h2>
	<?= @$person->bio; ?>
</div>