<? /** $Id$ */ ?>
<? defined('_JEXEC') or die('Restricted access'); ?>

<? @helper('behavior.tooltip'); ?>
<? @style(@$mediaurl.'/com_beer/css/form.css'); ?>

<? $editor =& KFactory::get('lib.joomla.editor', array('tinymce')); ?>

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
<form action="<?= @route('&id='. @$department->id)?>" method="post" class="adminform" name="adminForm">
	<div style="width:100%; float: left" id="mainform">
		<fieldset>
			<legend><?= @text('Details'); ?></legend>
			<label for="title" id="mainlabel"><?= @text('Title'); ?></label><input id="title" type="text" name="title" value="<?= @$department->title; ?>" /><br />
			<label for="alias" id="mainlabel"><?= @text('Alias'); ?></label><input id="alias" type="text" name="alias" value="<?= @$department->alias; ?>" /><br />
			<label for="enabled" id="mainlabel"><?= @text('Published'); ?></label><?= @helper('select.booleanlist', 'enabled', null, @$department->enabled, 'yes', 'no', 'enabled'); ?><br />
		</fieldset>
		<fieldset>
			<legend><?= @text('Description'); ?></legend>
			<?= $editor->display( 'description',  @$department->description , '100%', '50', '75', '20', null, array('theme' => 'simple')) ; ?>
		</fieldset>
	</div>
	<input type="hidden" name="id" value="<?= @$department->id ?>" />
</form>