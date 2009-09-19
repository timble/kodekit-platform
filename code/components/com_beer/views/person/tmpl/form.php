<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? @helper('behavior.tooltip'); ?>
<? @style(@$mediaurl.'/com_beer/css/form.css'); ?>
<? @script('/joomla/includes/js/joomla.javascript.js') ?>
<? @script(@$mediaurl.'/system/js/validate.js') ?>
<? $editor = KFactory::get('lib.joomla.editor', array('tinymce')); ?>

<script language="javascript" type="text/javascript">
	function checksubmit(form) {
		var submitOK=true;
		var checkaction=form.action.value;
		// do field validation
		if (checkaction=='cancel') {
			return true;
		}
		if (form.firstname.value == ""){
			alert( "<?php echo JText::_( 'Profile must have a firstname', true ); ?>" );
			submitOK=false;
			// remove the action field to allow another submit
			form.action.remove();
		}
		return submitOK;
	}
</script>
<h1 class="componentheading"><?= @text('Edit Profile') ?></h1>
<form action="<?= @route('&id='. @$person->id)?>" method="post" class="adminform" name="adminForm">
	<div style="width:100%; float: left"  id="mainform">
		<fieldset>
			<legend><?= @text('Details'); ?></legend>
				<label for="firstname" id="mainlabel"><?= @text('Firstname'); ?></label><input class="required" id="firstname" type="text" name="firstname" value="<?= @$person->firstname; ?>" /><br />
				<label for="middlename" id="mainlabel"><?= @text('Middlename'); ?></label><input id="middlename" type="text" name="middlename" value="<?= @$person->middlename; ?>" /><br />
				<label for="lastname" id="mainlabel"><?= @text('Lastname'); ?></label><input class="required" id="lastname" type="text" name="lastname" value="<?= @$person->lastname; ?>" /><br />
				<label for="birthday" id="mainlabel"><?= @text('Birthday'); ?></label><input id="birthday" type="text" name="birthday" value="<?= @$person->birthday; ?>" /><br />
				<label for="gender" id="mainlabel"><?= @text('Gender'); ?></label><?=@helper('admin::com.beer.helper.select.gender', @$person->gender, 'gender', '', '', true) ?><br />
				<label for="mobile" id="mainlabel"><?= @text('Mobile'); ?></label><input id="mobile" type="text" name="mobile" value="<?= @$person->mobile; ?>" /><br />
				<label for="email" id="mainlabel"><?= @text('Email'); ?></label><input id="email" type="text" name="email" value="<?= @$person->email; ?>" /><br />
		</fieldset>
		<fieldset>
			<legend><?= @text('Bio'); ?></legend>
			<?= $editor->display( 'bio',  @$person->bio , '100%', '50', '75', '20', null, array('theme' => 'simple')) ; ?>
		</fieldset>
			<button class="button validate" type="submit" onclick="submitbutton( this.form );return false;">Save</button>
	</div>
	<input type="hidden" name="id" value="<?= @$person->id ?>" />
</form>