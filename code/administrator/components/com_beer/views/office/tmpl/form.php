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
		if (form.title.value == ""){
			alert( "<?php echo JText::_( 'Office must have a title', true ); ?>" );
		} else {
			submitform( pressbutton );
		}
	}
</script>
<form action="<?= @route('&id='. @$office->id)?>" method="post" class="adminform" name="adminForm">
	<div style="width:100%; float: left">
		<fieldset>
			<legend><?= @text('Details'); ?></legend>
			<dl class="list">
				<dt><label><?= @text('Title'); ?></label></dt>
				<dd>
					<input id="title_field" type="text" name="title" value="<?= @$office->title; ?>" />
				</dd>
			</dl>
		</fieldset>

	</div>
	<input type="hidden" name="id" value="<?= @$office->id ?>" />
</form>