<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? @style(@$mediaurl.'/com_beer/css/grid.css') ?>
<? @style(@$mediaurl.'/com_beer/css/beer_admin.css') ?>

<div style="width:49%;float:left;">
	<div style="text-align:center;">
		<h3><?= @text('Offices Population')?></h3>
		<img src="<?=@helper('admin::com.beer.helper.chart.pie', 'offices') ?>" alt="<?= @text('Offices Population')?>" />
	</div>
	<h3><?= @text('Largest offices'); ?></h3>
	<?= @template('default_offices'); ?>
</div>

<div style="width:49%;float:right;">
	<div style="text-align:center;">
		<h3><?= @text('Departments Population')?></h3>
		<img src="<?=@helper('admin::com.beer.helper.chart.pie', 'departments') ?>" alt="<?= @text('Departments Population')?>" />
	</div>
	<h3><?= @text('Largest Departments'); ?></h3>	
	<?= @template('default_departments'); ?>
</div>

<h3><?= @text('Latest People'); ?></h3>
<?= @template('default_people'); ?>