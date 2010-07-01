<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<?= @helper('behavior.tooltip'); ?>
<?= @helper('behavior.modal'); ?>

<style src="media://com_default/css/form.css" />
<style src="media://com_profiles/css/admin.css" />
<style src="media://com_terms/css/default.css" />

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
			alert( "<?php echo JText::_( 'Profile must have a firstname', true ); ?>" );
			submitOK=false;
			// remove the action field to allow another submit
			form.action.remove();
		}
		return submitOK;
	}

	opennewframe = function () 
	{
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
			});
		}
</script>

<form action="<?= @route('&id='.$person->id)?>" method="post" name="adminForm">
	<div style="width:28%; float: left" id="mainform">
		<fieldset>
			<legend><?= @text('Details'); ?></legend>
				<label for="firstname" class="mainlabel"><?= @text('Firstname'); ?></label>
				<input id="firstname" type="text" name="firstname" value="<?= $person->firstname; ?>" />
				<br />
				<label for="middlename" class="mainlabel"><?= @text('Middlename'); ?></label>
				<input id="middlename" type="text" name="middlename" value="<?= $person->middlename; ?>" />
				<br />
				<label for="lastname" class="mainlabel"><?= @text('Lastname'); ?></label>
				<input id="lastname" type="text" name="lastname" value="<?= $person->lastname; ?>" />
				<br />
				<label for="slug" class="mainlabel"><?= @text('Slug'); ?></label>
				<input id="slug" type="text" name="slug" value="<?= $person->slug; ?>"/>
				<br /><br />
				<label for="office" class="mainlabel"><?= @text('Office'); ?></label>
				<?=@helper('admin::com.profiles.helper.listbox.offices', array('state' => $person)) ?>
				<br />
				<label for="department" class="mainlabel"><?= @text('Department'); ?></label>
				<?=@helper('admin::com.profiles.helper.listbox.departments', array('state' => $person)) ?>
				<br />
				<label for="position" class="mainlabel"><?= @text('Position'); ?></label>
				<input id="position" type="text" name="position" value="<?= $person->position; ?>" />
				<br /><br />
				<label for="birthday" class="mainlabel"><?= @text('Birthday'); ?></label>
				<input id="birthday" type="text" name="birthday" value="<?= $person->birthday; ?>" />
				<br />
				<label for="gender" class="mainlabel"><?= @text('Gender'); ?></label>
				<?=@helper('admin::com.profiles.helper.listbox.genders',  array('state' => $person)) ?>
				<br />
				<label for="mobile" class="mainlabel"><?= @text('Mobile'); ?></label>
				<input id="mobile" type="text" name="mobile" value="<?= $person->mobile; ?>" />
				<br />
				<label for="email" class="mainlabel"><?= @text('Email'); ?></label>
				<input id="email" type="text" name="email" value="<?= $person->email; ?>"/>
				<br /><br />
				<label for="enabled" class="mainlabel"><?= @text('Published'); ?></label>
				<?= @helper('select.booleanlist', array('name' => 'enabled', 'selected' => $person->enabled)); ?>
				<br />
		</fieldset>
		<fieldset>
			<legend><?= @text('Linked To'); ?></legend>
				<label for="user_id" class="mainlabel"><?= @text('User'); ?></label>
				<?//@helper('admin::com.profiles.helper.select.users', @$person->user_id, 'user_id', '', '', true) ?>
				<? if ($person->user_id) : ?>
				<a class="modal" rel="{handler: 'iframe', size: {x: 875, y: 500}}" href="<?= @route('option=com_users&task=edit&view=user&tmpl=component&cid[]='.$person->user_id)?>">
					<?= @text('Open User Profile'); ?>
				</a>
				<? else : ?>
				<a class="modal newselect" rel="{handler: 'iframe', size: {x: 875, y: 500}, onOpen: opennewframe}" href="<?= @route('option=com_users&task=view&view=users&tmpl=component') ?>">
					<?= @text('Select User'); ?>
				</a>
				<? endif; ?>
				<br />
				<label for="user_name" class="mainlabel"><?= @text('Name'); ?></label>
				<input id="user_name" type="text" name="user_name" value="<?= $person->user_name; ?>" disabled="disabled" />
				<br />
				<label for="user_username" class="mainlabel"><?= @text('Username'); ?></label>
				<input id="user_username" type="text" name="user_username" value="<?= $person->user_username; ?>" disabled="disabled" />
				<br />
				<label for="user_email" class="mainlabel"><?= @text('E-mail'); ?></label>
				<input id="user_email" type="text" name="user_email" value="<?= $person->user_email; ?>" disabled="disabled" />
		</fieldset>
	</div>
	<div style="width:48%; float: left; margin-left: 12px" id="mainform">
		<fieldset>
			<legend><?= @text('Bio'); ?></legend>
			<?= @editor(array('name' => 'bio', 'row' => $person,'options' => array('theme' => 'simple'))) ?>
		</fieldset>

	</div>
</form>
<div style="width:22%; float: right">
	<fieldset>
		<legend><?= @text('Tags'); ?></legend>
		<?= @overlay(array('uri' => @route('option=com_terms&view=terms&row='.$person->id.'&table=profiles_people&layout=list#terms-list'))); ?>
	</fieldset>
</div>