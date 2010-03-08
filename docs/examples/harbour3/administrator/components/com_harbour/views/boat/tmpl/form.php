<? defined('KOOWA') or die('Restricted access'); ?>

<form action="<?= @route('id='.@$boat->id) ?>" method="post" class="adminform" name="adminForm">
	<dl>
		<dt>
			<label for="name_field"><?= @text('Boat name'); ?></label>
		</dt>
		<dd>
			<input id="name_field" type="text" name="name" value="<?= @$boat->name; ?>" />
		</dd>
	<dl>
		<dt>
			<label for="description_field"><?= @text('Description'); ?></label>
		</dt>
		<dd>
			<?= KFactory::get('lib.koowa.editor', array('tinymce'))->display( 'description',  @$boat->description , '600', '300', '100', '20', null, array('theme' => 'simple')) ; ?>
		</dd>
</form>
