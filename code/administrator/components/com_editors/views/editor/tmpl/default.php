<style src="media://com_editors/codemirror/css/docs.css" />

<?= @template('default_script') ?>

<textarea id="<?= $id ?>" name="<?= $name ?>" class="editable-<?= $id ?> validate-editor" style="visibility:hidden"><?= $data ?></textarea>