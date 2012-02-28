<style src="media://com_editors/codemirror/css/docs.css" />

<?= @template('default_script') ?>

<textarea id="<?= $name ?>" name="<?= $name ?>" cols="75" rows"25" class="editable-<?= $name ?> validate-editor"><?= $data ?></textarea>