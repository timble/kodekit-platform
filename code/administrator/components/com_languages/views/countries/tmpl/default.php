<style src="media://com_languages/css/countries-default.css" />
<script src="media://com_languages/js/countries-default.js" />

<form id="countries-form" action="" method="post" class="-koowa-grid">
<fieldset>
	<div style="float: right">
		<button type="button" onclick="window.parent.document.getElementById('sbox-window').close();">
			<?= @text( 'Cancel' );?></button>
	</div>
	<div class="configuration" >
		<?= @text('Pick a flag') ?>
	</div>
</fieldset>

<div>	
<? foreach($countries as $country) : ?>
	<a href="javascript:clickFlag('<?= $country->code ?>')" class="flag_select_wrapper">
		<img class="flag_select_img" src="media://<?= $country->flag ?>" />
		<div class="flag_select_country"><?= $country->title ?></div>
	</a>
<? endforeach; ?>
</div>	
<?= @helper('paginator.pagination', array('total' => $total, 'show_limit' => false)) ?>
</form>