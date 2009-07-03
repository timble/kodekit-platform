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
<form action="<?= @route('&id='. @$office->id)?>" method="post" class="adminform" name="adminForm">
	<div style="width:100%; float: left">
		<fieldset>
			<legend><?= @text('Details'); ?></legend>
			<dl class="list">
				<dt><label><?= @text('Title'); ?></label></dt>
				<dd>
					<input id="title_field" type="text" name="title" value="<?= @$office->title; ?>" />
				</dd>
				<dt><label><?= @text('Alias'); ?></label></dt>
				<dd>
					<input id="alias_field" type="text" name="alias" value="<?= @$office->alias; ?>" />
				</dd>
				<dt><label><?= @text('Published'); ?></label></dt>
				<dd>
					<?= @helper('select.booleanlist', 'enabled', null, @$office->enabled, 'yes', 'no', 'enabled'); ?>
				</dd>
			</dl>
		</fieldset>
		<fieldset>
			<legend><?= @text('Location'); ?></legend>
			<dl class="list">
				<dt><label><?= @text('Address'); ?> 1</label></dt>
				<dd>
					<input id="address1_field" type="text" name="address1" value="<?= @$office->address1; ?>" />
				</dd>
				<dt><label><?= @text('Address'); ?> 2</label></dt>
				<dd>
					<input id="address2_field" type="text" name="address2" value="<?= @$office->address2; ?>" />
				</dd>
				<dt><label><?= @text('City'); ?></label></dt>
				<dd>
					<input id="city_field" type="text" name="city" value="<?= @$office->city; ?>" />
				</dd>
				<dt><label><?= @text('State'); ?></label></dt>
				<dd>
					<input id="state_field" type="text" name="state" value="<?= @$office->state; ?>" />
				</dd>
				<dt><label><?= @text('City'); ?></label></dt>
				<dd>
					<input id="postcode_field" type="text" name="postcode" value="<?= @$office->postcode; ?>" />
				</dd>
				<dt><label><?= @text('Country'); ?></label></dt>
				<dd>
					<input id="country_field" type="text" name="country" value="<?= @$office->country; ?>" />
				</dd>
				<dt><label><?= @text('Phone'); ?></label></dt>
				<dd>
					<input id="phone_field" type="text" name="phone" value="<?= @$office->phone; ?>" />
				</dd>
				<dt><label><?= @text('Fax'); ?></label></dt>
				<dd>
					<input id="fax_field" type="text" name="fax" value="<?= @$office->fax; ?>" />
				</dd>
			</dl>
		</fieldset>
		<fieldset>
			<legend><?= @text('Description'); ?></legend>
			<?= $editor->display( 'description',  @$office->description , '100%', '50', '75', '20', null, array('theme' => 'simple')) ; ?>
		</fieldset>
	</div>
	<input type="hidden" name="id" value="<?= @$office->id ?>" />
</form>