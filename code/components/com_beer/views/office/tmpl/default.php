<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>
<? @style(@$mediaurl.'/com_beer/css/default.css'); ?>
<?php JHTML::_('behavior.mootools'); ?>
<? @script('http://maps.google.com/maps/api/js?sensor=false'); ?>
<? @script(@$mediaurl.'/com_beer/js/site.office.js'); ?>

<h1 class="componentheading"><?= @$office->title; ?></h1>
<div id="beer_info">
	<img src="<?= @$mediaurl;?>/com_beer/images/flags/<?= strtolower(@$office->country);?>.png" alt="<?= @$office->country;?> flag"/><?= @$office->address1 . ' ' . @$office->address2 . ' ' . @$office->city . ' ' . @$office->state . ' ' . @$office->postcode; ?>
	<span class="phone"><?= @$office->phone; ?></spam>
	<span class="fax"><?= @$office->fax; ?></span>
	<span class="people"><a href="<?=@route('option=com_beer&view=people&beer_office_id='.@$office->id) ?>"><?= @$office->people; ?> <?=@text('Employee(s)')?></a></span>
	<span class="map"><a href="#" onclick="showAddress(<?= @$office->address1 . ' ' . @$office->address2 . ', ' . @$office->city . ', ' . @$office->state . ', ' . @$office->postcode . ', ' . @$office->country; ?>); return false"><?= @text('Map'); ?></a></span>
	</div>
<div id="beer_desc">
	<h2><?= @text('Information'); ?></h2>
	<?= @$office->description; ?>
</div>
<div id="map_canvas" rel="50.9873946, 5.0474845" style="width: 500px; height: 300px"></div>





