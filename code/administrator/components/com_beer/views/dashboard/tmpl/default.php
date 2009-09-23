<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? @style(@$mediaurl.'/com_beer/css/grid.css') ?>
<? @style(@$mediaurl.'/com_beer/css/beer_admin.css') ?>

<div style="width:49%;float:left;">
	<?= @helper('tabs.startPane', 'graphs', array('height' => '275px')) ?>
	
	<?= @helper('tabs.startPanel', @text('Offices')) ?>
	    <div style="text-align:center;">
	    	<h3><?= @text('Population')?></h3>
	        <img src="<?=@helper('admin::com.beer.helper.chart.pie', 'offices') ?>" alt="<?= @text('Offices')?>" />
	    </div>
	<?= @helper('tabs.endPanel') ?>
	
	<?= @helper('tabs.startPanel', @text('Departments')) ?>
	    <div style="text-align:center;">
	    	<h3><?= @text('Population')?></h3>
	        <img src="<?=@helper('admin::com.beer.helper.chart.pie', 'departments') ?>" alt="<?= @text('Departments')?>" />
	    </div>
 	<?= @helper('tabs.endPanel') ?>
 	
	<?= @helper('tabs.endPane') ?>
</div>

	
<div style="width:49%;float:right;">

	<?= @helper('tabs.startPane', 'items', array('height' => '275px')) ?>
	
	<?= @helper('tabs.startPanel', @text('Largest Departments')) ?>
    <?= @template('default_offices'); ?>
    <?= @helper('tabs.endPanel') ?>
    
    <?= @helper('tabs.startPanel', @text('Largest Ooffices')) ?>
    <?= @template('default_departments'); ?>
    <?= @helper('tabs.endPanel') ?>
    
    <?= @helper('tabs.startPanel', @text('Latest People')) ?>
	<?= @template('default_offices'); ?>
    <?= @helper('tabs.endPanel') ?>
    
    <?= @helper('tabs.endPane') ?>

</div>

<?= @template('default_footer'); ?>
