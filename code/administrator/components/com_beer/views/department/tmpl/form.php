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
	<div style="width:100%; float: left">
		<fieldset>
			<legend><?= @text('Details'); ?></legend>
			<dl class="list">
				<dt><label><?= @text('Title'); ?></label></dt>
				<dd>
					<input id="title_field" type="text" name="title" value="<?= @$department->title; ?>" />
				</dd>
				<dt><label><?= @text('Alias'); ?></label></dt>
				<dd>
					<input id="alias_field" type="text" name="alias" value="<?= @$department->alias; ?>" />
				</dd>
				<dt><label><?= @text('Published'); ?></label></dt>
				<dd>
					<?= @helper('select.booleanlist', 'enabled', null, @$department->enabled, 'yes', 'no', 'enabled'); ?>
				</dd>
			</dl>
		</fieldset>
		<fieldset>
			<legend><?= @text('Description'); ?></legend>
			<?= $editor->display( 'description',  @$department->description , '100%', '50', '75', '20', null, array('theme' => 'simple')) ; ?>
		</fieldset>

	</div>
	<input type="hidden" name="id" value="<?= @$department->id ?>" />
</form>