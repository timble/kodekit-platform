<?
/**
 * @version		$Id: form.php 1294 2011-05-16 22:57:57Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Search
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<form action="<?= @route('option=com_search'); ?>" method="get" <?= $form_class ? 'class="'.$form_class.'"' : '' ?>>
	<input name="term" <?= $input_class ? 'class="'.$input_class.'"' : '' ?> type="text" placeholder="<?= $placeholder ?>" />
</form>