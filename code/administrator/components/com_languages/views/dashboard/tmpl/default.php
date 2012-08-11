<? /** $Id: default.php 777 2008-10-19 22:18:08Z mathias $ */ ?>
<? defined('_JEXEC') or die('Restricted access'); ?>

<? @helper('behavior.tooltip'); ?>

<div style="width:49%;float:left;">
	<? if(@$multiple_contributors): ?>
	    <div style="text-align:center;">
	        <h3><?= @text('Translators')?></h3>
	        <img src="<?=@$translators?>" alt="<?= @text('Translators')?>" />
	    </div>
	<? endif; ?>

    <?= @template('additions'); ?>
    <?= @template('changes'); ?>
    <?= @template('deletes'); ?>

</div>

<div style="width:49%;float:right;">

	<? if(@$multiple_langs && @$has_tables): ?>
	    <div style="text-align:center;">
	        <h3><?= @text('Translations')?></h3>
	        <img src="<?=@$translations?>" alt="<?= @text('Translations')?>" />
	    </div>
	<? endif;?>

    <?= @template('languages'); ?>
    <?= @template('translators'); ?>
    <?= @template('tables'); ?>
</div>

<?= @template('footer'); ?>
