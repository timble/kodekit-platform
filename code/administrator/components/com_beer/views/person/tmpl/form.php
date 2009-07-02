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
			</dl>
		</fieldset>

	</div>
	<input type="hidden" name="id" value="<?= @$person->id ?>" />
</form>