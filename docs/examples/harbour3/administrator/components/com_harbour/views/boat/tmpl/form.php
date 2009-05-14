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
			<textarea id="description_field" name="description"><?= @$boat->description;?></textarea>
		</dd>
</form>
