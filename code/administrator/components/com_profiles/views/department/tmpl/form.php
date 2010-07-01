<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<?= @helper('behavior.tooltip'); ?>
<style src="media://com_default/css/form.css" />
<style src="media://com_profiles/css/admin.css" />

<script>
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

<form action="<?= @route('&id='.$department->id)?>" method="post" name="adminForm">
	<div style="width:100%; float: left" id="mainform">
		<fieldset>
			<legend><?= @text('Details'); ?></legend>
			<label for="title" class="mainlabel"><?= @text('Title'); ?></label>
			<input id="title" type="text" name="title" value="<?= $department->title; ?>" />
			<br />
			<label for="slug" class="mainlabel"><?= @text('Slug'); ?></label>
			<input id="slug" type="text" name="slug" value="<?= $department->slug; ?>" />
			<br />
			<label for="enabled" class="mainlabel"><?= @text('Published'); ?></label>
			<?= @helper('select.booleanlist', array('name' => 'enabled', 'selected' => $department->enabled)); ?>
		</fieldset>
		<fieldset>
			<legend><?= @text('Description'); ?></legend>
			<?= @editor(array('row' => $department, 'buttons' => array('pagebreak', 'readmore'),  'options' => array('theme' => 'simple'))) ?>
		</fieldset>
	</div>
</form>