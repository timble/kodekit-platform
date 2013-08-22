<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<form action="<?= route('row='.$state->row.'&table='.$state->table) ?>" method="post">
    <input type="hidden" name="row" value="<?= $state->row ?>" />
    <input type="hidden" name="table" value="<?= $state->table ?>" />

    <textarea type="text" name="text" placeholder="<?= translate('Add new comment here ...') ?>" id="new-comment-text"></textarea>
    <br />
    <input class="button" type="submit" value="<?= translate('Submit') ?>"/>
</form>