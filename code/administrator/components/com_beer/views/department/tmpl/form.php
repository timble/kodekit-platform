<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? @helper('behavior.tooltip'); ?>
<? @style(@$mediaurl.'/com_beer/css/form.css'); ?>
<? @style(@$mediaurl.'/com_beer/css/beer_admin.css') ?>

<script language="javascript" type="text/javascript">
	function checksubmit(form) {
		var submitOK=true;
		var checkaction=form.action.value;
		// do field validation
		if (checkaction=='cancel') {
			return true;
		}
		if (form.title.value == ""){
			alert( "<?= @text( 'Department must have a title', true ); ?>" );
			submitOK=false;
			// remove the action field to allow another submit
			form.action.remove();
		}
		return submitOK;
	}
</script>

<form action="<?= @route('&id='.@$department->id)?>" method="post" class="adminform" name="adminForm">
	<div style="width:100%; float: left" id="mainform">
		<fieldset>
			<legend><?= @text('Details'); ?></legend>
			<label for="title" id="mainlabel"><?= @text('Title'); ?></label>
			<input id="title" type="text" name="title" value="<?= @$department->title; ?>" />
			<br />
			<label for="alias" id="mainlabel"><?= @text('Alias'); ?></label>
			<input id="alias" type="text" name="alias" value="<?= @$department->alias; ?>" />
			<br />
			<label for="enabled" id="mainlabel"><?= @text('Published'); ?></label>
			<?= @helper('select.booleanlist', 'enabled', null, @$department->enabled, 'yes', 'no', 'enabled'); ?>
		</fieldset>
		<fieldset>
			<legend><?= @text('Description'); ?></legend>
			<?= KFactory::get('lib.joomla.editor', array('tinymce'))->display( 'description',  @$department->description , '100%', '50', '75', '20', null, array('theme' => 'simple')) ; ?>
		</fieldset>
	</div>
</form>