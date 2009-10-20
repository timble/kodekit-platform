<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? @helper('behavior.tooltip'); ?>
<? @helper('behavior.modal'); ?>
<? @style(@$mediaurl.'/com_profiles/css/form.css'); ?>
<? @style(@$mediaurl.'/com_profiles/css/admin.css') ?>

<? @script(@$mediaurl.'/com_tags/js/view.js') ?>
<script>var row_id = <?= (int) @$person->id?>;</script>
<script>var table_name = 'profiles_people';</script>

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

	opennewframe = function () {
		// remove rows
		// change index.php?option=com_users&view=user&task=edit&cid[]=62
		// This adds a class to the iframe
		$$('#sboxiframe').addClass('test');
		// But this fails to select anything within the iframe
		$$('#sboxiframe .adminlist').addClass('test');
		// Same for this
		$$('#sboxiframe').getElement('.adminlist').each(
			function(item, index) {
				item.addClass('test');
			}
			)
		}
</script>

<form action="<?= @route('&id='.@$person->id)?>" method="post" class="adminform" name="adminForm">
	<div style="width:39%; float: left" id="mainform">
		<fieldset>
			<legend><?= @text('Details'); ?></legend>
				<label for="firstname" id="mainlabel"><?= @text('Firstname'); ?></label>
				<input id="firstname" type="text" name="firstname" value="<?= @$person->firstname; ?>" />
				<br />
				<label for="middlename" id="mainlabel"><?= @text('Middlename'); ?></label>
				<input id="middlename" type="text" name="middlename" value="<?= @$person->middlename; ?>" />
				<br />
				<label for="lastname" id="mainlabel"><?= @text('Lastname'); ?></label>
				<input id="lastname" type="text" name="lastname" value="<?= @$person->lastname; ?>" />
				<br />
				<label for="office" id="mainlabel"><?= @text('Office'); ?></label>
				<?=@helper('admin::com.profiles.helper.select.offices', @$person->profiles_office_id, 'profiles_office_id', '', '', true) ?>
				<br />
				<label for="departmen" id="mainlabel"><?= @text('Department'); ?></label>
				<?=@helper('admin::com.profiles.helper.select.departments', @$person->profiles_department_id, 'profiles_department_id', '', '', true) ?>
				<br />
				<label for="position" id="mainlabel"><?= @text('Position'); ?></label>
				<input id="position" type="text" name="position" value="<?= @$person->position; ?>" />
				<br />
				<label for="birthday" id="mainlabel"><?= @text('Birthday'); ?></label>
				<input id="birthday" type="text" name="birthday" value="<?= @$person->birthday; ?>" />
				<br />
				<label for="gender" id="mainlabel"><?= @text('Gender'); ?></label>
				<?=@helper('admin::com.profiles.helper.select.gender', @$person->gender, 'gender', '', '', true) ?>
				<br />
				<label for="mobile" id="mainlabel"><?= @text('Mobile'); ?></label>
				<input id="mobile" type="text" name="mobile" value="<?= @$person->mobile; ?>" />
				<br />
				<label for="email" id="mainlabel"><?= @text('Email'); ?></label>
				<input id="email" type="text" name="email" value="<?= @$person->email; ?>"/>
				<br />
				<label for="enabled" id="mainlabel"><?= @text('Published'); ?></label>
				<?= @helper('select.booleanlist', 'enabled', null, @$person->enabled, 'yes', 'no', 'enabled'); ?>
				<br />
		</fieldset>
		<fieldset>
			<legend><?= @text('Linked To'); ?></legend>
				<label for="user_id" id="mainlabel"><?= @text('User'); ?></label>
				<?//@helper('admin::com.profiles.helper.select.users', @$person->user_id, 'user_id', '', '', true) ?>
				<? if (@$person->user_id) : ?>
				<a class="modal" rel="{handler: 'iframe', size: {x: 875, y: 500}}" href="<?= @route('option=com_users&task=edit&view=user&tmpl=component&cid[]='.@$person->user_id)?>">
					<?= @text('Open User Profile'); ?>
				</a>
				<? else : ?>
				<a class="modal newselect" rel="{handler: 'iframe', size: {x: 875, y: 500}, onOpen: opennewframe}" href="<?= @route('option=com_users&task=view&view=users&tmpl=component') ?>">
					<?= @text('Select User'); ?>
				</a>
				<? endif; ?>
				<br />
				<label for="user_name" id="mainlabel"><?= @text('Name'); ?></label>
				<input id="user_name" type="text" name="user_name" value="<?= @$person->user_name; ?>" disabled="disabled" />
				<br />
				<label for="user_username" id="mainlabel"><?= @text('Username'); ?></label>
				<input id="user_username" type="text" name="user_username" value="<?= @$person->user_username; ?>" disabled="disabled" />
				<br />
				<label for="user_email" id="mainlabel"><?= @text('E-mail'); ?></label>
				<input id="user_email" type="text" name="user_email" value="<?= @$person->user_email; ?>" disabled="disabled" />
		</fieldset>
	</div>
	<div style="width:59%; float: right" id="mainform">
		<fieldset>
			<legend><?= @text('Bio'); ?></legend>
			<?= KFactory::get('lib.joomla.editor', array('tinymce'))->display( 'bio',  @$person->bio , '100%', '370', '100', '20', null, array('theme' => 'simple')) ; ?>
		</fieldset>

	</div>
</form>

<div style="width:100%; float: right">
	<fieldset>
		<legend><?= @text('Tags'); ?></legend>
		<div id="tags_panel">
		</div>
	</fieldset>
</div>