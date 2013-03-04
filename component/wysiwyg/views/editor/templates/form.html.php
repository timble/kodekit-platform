<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

?>

<?= @template('form_script.html') ?>

<textarea id="<?= $id ?>" name="<?= $name ?>" class="editable-<?= $id ?> validate-editor" style="visibility:hidden"><?= $text ?></textarea>