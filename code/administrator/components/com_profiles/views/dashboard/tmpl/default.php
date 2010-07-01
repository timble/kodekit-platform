<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<style src="media://com_profiles/css/admin.css" />

<div style="width:49%;float:left;">
	<?= @helper('tabs.startPane', array('id' => 'graphs', 'attribs' => array('height' => '275px'))) ?>
	
	<?= @helper('tabs.startPanel', array('title' => @text('Offices'))) ?>
	    <div style="text-align:center;">
	    	<h3><?= @text('Population')?></h3>
	        <img src="<?= @helper('admin::com.profiles.helper.chart.pie', 'offices') ?>" alt="<?= @text('Offices')?>" />
	    </div>
	<?= @helper('tabs.endPanel') ?>
	
	<?= @helper('tabs.startPanel', array('title' => @text('Departments'))) ?>
	    <div style="text-align:center;">
	    	<h3><?= @text('Population')?></h3>
	        <img src="<?=@helper('admin::com.profiles.helper.chart.pie', 'departments') ?>" alt="<?= @text('Departments')?>" />
	    </div>
 	<?= @helper('tabs.endPanel') ?>
 	
	<?= @helper('tabs.endPane') ?>
</div>

	
<div style="width:49%;float:right;">

	<?= @helper('tabs.startPane', array('id' => 'items', 'attribs' => array('height' => '275px'))) ?>
	
	<?= @helper('tabs.startPanel', array('title' => @text('Largest Departments'))) ?>
    <?= KFactory::tmp('admin::com.profiles.controller.department')
    	->sort('people')
    	->direction('desc')
    	->limit(5)
    	->layout('table')
    	->browse(); 
	?>
    <?= @helper('tabs.endPanel') ?>
    
    <?= @helper('tabs.startPanel', array('title' => @text('Largest Offices'))) ?>
    <?= KFactory::tmp('admin::com.profiles.controller.office')
    	->sort('people')
    	->direction('desc')
    	->limit(5)
    	->layout('table')
    	->browse(); 
	?>
    <?= @helper('tabs.endPanel') ?>
    
    <?= @helper('tabs.startPanel', array('title' => @text('Latest People'))) ?>
	<?= KFactory::tmp('admin::com.profiles.controller.person')
    	->sort('created_on')
    	->direction('desc')
    	->limit(5)
    	->layout('table')
    	->browse(); 
	?>
    <?= @helper('tabs.endPanel') ?>
    
    <?= @helper('tabs.endPane') ?>

</div>

<?= @template('default_footer'); ?>
