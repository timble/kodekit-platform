<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<?= @helper('behavior.tooltip'); ?>
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://com_profiles/css/form.css" />
<script src="/joomla/includes/js/joomla.javascript.js" />
<script src="media://system/js/validate.js" />

<script>
	function checksubmit(form) 
	{
		var submitOK=true;
		var checkaction=form.action.value;
		// do field validation
		if (checkaction=='cancel') {
			return true;
		}
		if (form.firstname.value == ""){
			alert( "<?= JText::_( 'Profile must have a firstname', true ); ?>" );
			submitOK=false;
			// remove the action field to allow another submit
			form.action.remove();
		}
		return submitOK;
	}
</script>

<h1 class="componentheading"><?= @text('Edit Profile') ?></h1>
<form action="<?= @route('&id='. $person->id)?>" method="post" class="adminform" name="adminForm">
	<div style="width:100%; float: left"  id="mainform">
		<fieldset>
			<legend><?= @text('Details'); ?></legend>
				<label for="firstname" id="mainlabel"><?= @text('Firstname'); ?></label>
				<input class="required" id="firstname" type="text" name="firstname" value="<?= $person->firstname; ?>" />
				<br />
				<label for="middlename" id="mainlabel"><?= @text('Middlename'); ?></label>
				<input id="middlename" type="text" name="middlename" value="<?= $person->middlename; ?>" />
				<br />
				<label for="lastname" id="mainlabel"><?= @text('Lastname'); ?></label>
				<input class="required" id="lastname" type="text" name="lastname" value="<?= $person->lastname; ?>" />
				<br />
				<label for="birthday" id="mainlabel"><?= @text('Birthday'); ?></label>
				<input id="birthday" type="text" name="birthday" value="<?= $person->birthday; ?>" />
				<br />
				<label for="gender" id="mainlabel"><?= @text('Gender'); ?></label>
				<?=@helper('admin::com.profiles.helper.listbox.genders', array('state' => $person)) ?>
				<br />
				<label for="mobile" id="mainlabel"><?= @text('Mobile'); ?></label>
				<input id="mobile" type="text" name="mobile" value="<?= $person->mobile; ?>" />
				<br />
				<label for="email" id="mainlabel"><?= @text('Email'); ?></label>
				<input id="email" type="text" name="email" value="<?= $person->email; ?>" />
		</fieldset>
		<fieldset>
			<legend><?= @text('Bio'); ?></legend>
			<?= @editor(array('row' => $department, 'height' => '50',  'options' => array('theme' => 'simple'))) ?>
		</fieldset>
		<button class="button validate" type="submit" onclick="KForm.addField('action', 'save'); KForm.submit('post');">Save</button>
	</div>
</form>