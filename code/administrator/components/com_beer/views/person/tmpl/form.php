<? defined('_JEXEC') or die('Restricted access'); ?>

<? @helper('behavior.tooltip'); ?>
<? @style(@$mediaurl.'/com_beer/css/form.css'); ?>

<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		// do field validation
		if (form.firstname.value == ""){
			alert( "<?php echo JText::_( 'Profile must have a firstname', true ); ?>" );
		} else {
			submitform( pressbutton );
		}
	}
</script>
<form action="<?= @route('&id='. @$person->id)?>" method="post" class="adminform" name="adminForm">
	<div style="width:100%; float: left">
		<fieldset>
			<legend><?= @text('Details'); ?></legend>
			<dl class="list">
				<dt><label><?= @text('Firstname'); ?></label></dt>
				<dd>
					<input id="firstname_field" type="text" name="firstname" value="<?= @$person->firstname; ?>" />
				</dd>
				<dt><label><?= @text('Middlename'); ?></label></dt>
				<dd>
					<input id="middlename_field" type="text" name="middlename" value="<?= @$person->middlename; ?>" />
				</dd>
				<dt><label><?= @text('Lastname'); ?></label></dt>
				<dd>
					<input id="lastname_field" type="text" name="lastname" value="<?= @$person->lastname; ?>" />
				</dd>
				<dt><label><?= @text('Office'); ?></label></dt>
				<dd>
					<?=@helper('admin::com.beer.helper.select.offices', @$person->beer_office_id, 'beer_office_id', '', '', true) ?>
				</dd>
				<dt><label><?= @text('Department'); ?></label></dt>
				<dd>
					<?=@helper('admin::com.beer.helper.select.departments', @$person->beer_department_id, 'beer_department_id', '', '', true) ?>
				</dd>
				<dt><label><?= @text('Position'); ?></label></dt>
				<dd>
					<input id="position_field" type="text" name="position" value="<?= @$person->position; ?>" />
				</dd>
				<dt><label><?= @text('Birthday'); ?></label></dt>
				<dd>
					<input id="birthday_field" type="text" name="birthday" value="<?= @$person->birthday; ?>" />
				</dd>
				<dt><label><?= @text('Gender'); ?></label></dt>
				<dd>
					<input id="gender_field" type="text" name="gender" value="<?= @$person->gender; ?>" />
				</dd>
				<dt><label><?= @text('Mobile'); ?></label></dt>
				<dd>
					<input id="mobile_field" type="text" name="mobile" value="<?= @$person->mobile; ?>" />
				</dd>
				<dt><label><?= @text('Email'); ?></label></dt>
				<dd>
					<input id="email_field" type="text" name="email" value="<?= @$person->email; ?>" />
				</dd>
				<dt><label><?= @text('Published'); ?></label></dt>
				<dd>
					<?= @helper('select.booleanlist', 'enabled', null, @$person->enabled, 'yes', 'no', 'enabled'); ?>
				</dd>
			</dl>
		</fieldset>

	</div>
	<input type="hidden" name="id" value="<?= @$person->id ?>" />
</form>