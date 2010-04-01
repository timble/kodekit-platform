<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? $coordinates = array(
	'lat' => @helper('admin::com.profiles.helper.geocoding.coordinates', 'latitude', @$office),
	'lng' => @helper('admin::com.profiles.helper.geocoding.coordinates', 'longitude', @$office)
) ?>
<? @js('var coordinate_lat = ' . $coordinates['lat'] . ', coordinate_lng = ' . $coordinates['lng'] . ';') ?>
<script>var coordinate_lat = <?=@helper('admin::com.profiles.helper.geocoding.coordinates', 'latitude', @$office) ?>;</script>
<script>var coordinate_lng = <?=@helper('admin::com.profiles.helper.geocoding.coordinates', 'longitude', @$office) ?>;</script>

<? @helper('behavior.tooltip'); ?>
<? @style(@$mediaurl.'/com_profiles/css/form.css'); ?>
<? @js('/admin.office.js'); ?>
<? @style(@$mediaurl.'/com_profiles/css/admin.css') ?>
<? @js("http://maps.google.com/maps/api/js?sensor=false"); ?>

<script type="text/javascript">
	function checksubmit(form) {
		var submitOK=true;
		var checkaction=form.action.value;
		// do field validation
		if (checkaction=='cancel') {
			return true;
		}
		if (form.title.value == ""){
			alert( "<?= @text( 'Office must have a title', true ); ?>" );
			submitOK=false;
			// remove the action field to allow another submit
			form.action.remove();
		}
		return submitOK;
	}
</script>

<form action="<?= @route('&id='. @$office->id)?>" method="post" class="adminform" name="adminForm" id="<?= @helper('admin::com.profiles.helper.behavior.id') ?>">
	<div style="width:100%; float: left" id="mainform">
		<fieldset>
			<legend><?= @text('Details'); ?></legend>
				<label for="title" class="mainlabel"><?= @text('Title'); ?></label>
				<input id="title" type="text" name="title" value="<?= @$office->title; ?>" />
				<br />
				<label for="alias" class="mainlabel"><?= @text('Alias'); ?></label>
				<input id="alias" type="text" name="alias" value="<?= @$office->alias; ?>" />
				<br />
				<label for="enabled" class="mainlabel"><?= @text('Published'); ?></label>
				<?= @helper('select.booleanlist', 'enabled', null, @$office->enabled, 'yes', 'no', 'enabled'); ?>
		</fieldset>
		<fieldset>
			<legend><?= @text('Location'); ?></legend>
				<label for="country" class="mainlabel"><?= @text('Country'); ?></label>
				<?=@helper('admin::com.profiles.helper.iso.country', 'country', @$office->country, 'country', '', '', true) ?>
				<br />
				<label for="address1" class="mainlabel"><?= @text('Address'); ?> 1</label>
				<input id="address1" type="text" name="address1" value="<?= @$office->address1; ?>" />
				<br />
				<label for="address2" class="mainlabel"><?= @text('Address'); ?> 2</label>
				<input id="address2" type="text" name="address2" value="<?= @$office->address2; ?>" />
				<br />
				<label for="city" class="mainlabel"><?= @text('City'); ?></label>
				<input id="city" type="text" name="city" value="<?= @$office->city; ?>" />
				<br />
				<label for="state" class="mainlabel"><?= @text('State'); ?></label>
				<div id="statecontainer"><?=@helper('admin::com.profiles.helper.iso.states', @$office->country, 'state', @$office->state) ?></div>
				<br />
				<label for="postcode" class="mainlabel"><?= @text('Postcode'); ?></label>
				<input id="postcode" type="text" name="postcode" value="<?= @$office->postcode; ?>" />
				<br />
				<label for="phone" class="mainlabel"><?= @text('Phone'); ?></label>
				<input id="phone" type="text" name="phone" value="<?= @$office->phone; ?>" />
				<br />
				<label for="fax" class="mainlabel"><?= @text('Fax'); ?></label>
				<input id="fax" type="text" name="fax" value="<?= @$office->fax; ?>" />
		</fieldset>
		<fieldset>
			<legend><?= @text('Description'); ?></legend>
			<?= KFactory::get('lib.joomla.editor', array('tinymce'))->display( 'description',  @$office->description , '100%', '50', '75', '20', null, array('theme' => 'simple')) ; ?>
		</fieldset>
		<fieldset>
			<legend><?= @text('Map'); ?></legend>
			<div id="map_canvas" style="width: 100%; height: 300px"></div>
		</fieldset>
	</div>
</form>