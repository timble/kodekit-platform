<? /** $Id$ */ ?>
<? @style(@$mediaurl.'/com_beer/css/office.css'); ?>

<h3><?= @$office->title; ?></h3>
<div id="beer_address">
	<img src="<?= @$mediaurl;?>/com_beer/images/flags/<?= strtolower(@$office->country);?>.png" alt="<?= @$office->country;?> flag"/><?= @$office->adrdress1 . ' ' . @$office->adrdress2 . ' ' . @$office->city . ' ' . @$office->state . ' ' . @$office->p; ?>
	<span class="phone"><?= @$office->phone; ?></spam>
	<span class="fax"><?= @$office->fax; ?></span>
	<span class="people"><?= @$office->people; ?></span>
	<span class="map"><?= @text('Map'); ?></span>
	</div>
<div id="beer_desc">
	<?= @$office->description; ?>
</div>

