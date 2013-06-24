<?
/**
 * @package     Nooku_Server
 * @subpackage  Comments
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<div id="comments-comment-form">
	<form action="<?= @route('row='.@$state->row.'&table='.$state->table) ?>" method="post">
        <input type="hidden" name="row" value="<?= $state->row ?>" />
        <input type="hidden" name="table" value="<?= $state->table ?>" />

        <textarea type="text" name="text" placeholder="<?= @text('Add new comment here ...') ?>"></textarea>
        <br />
        <input class="button" type="submit" value="<?= @text('Submit') ?>"/>
    </form>
</div>