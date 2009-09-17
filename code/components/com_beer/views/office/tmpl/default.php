<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>
<? @style(@$mediaurl.'/com_beer/css/default.css'); ?>
<? @helper('behavior.mootools'); ?>

<h1 class="componentheading"><?= @$office->title; ?></h1>
<div id="beer_info">
	<img src="<?= @$mediaurl;?>/com_beer/images/flags/<?= strtolower(@$office->country);?>.png" alt="<?= @$office->country;?> flag"/>
	<?= @$office->address?>
	<span class="phone"><?= @$office->phone; ?></span>
	<span class="fax"><?= @$office->fax; ?></span>
	<span class="people"><a href="<?=@route('view=people&beer_office_id='.@$office->id) ?>"><?= @$office->people; ?> <?=@text('Employee(s)')?></a></span>
	<!-- @todo
	<span class="map"><a href="#" onclick="showAddress(<?= @$office->address1 . ' ' . @$office->address2 . ', ' . @$office->city . ', ' . @$office->state . ', ' . @$office->postcode . ', ' . @$office->country; ?>); return false"><?= @text('Map'); ?></a></span>
	 -->
</div>
<div id="beer_desc">
	<h2><?= @text('Information'); ?></h2>
	<?= @$office->description; ?>
</div>




