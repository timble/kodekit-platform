<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<form action="<?= @helper('com:comments.route.comment', array('row' => $row)) ?>" method="post">
    <input type="hidden" name="row" value="<?= $state->row ?>" />

    <textarea type="text" name="text" placeholder="<?= @text('Write a comment...') ?>" id="new-comment-text" class="input-block-level"></textarea>
    <input class="btn btn-primary" type="submit" value="<?= @text('Comment') ?>"/>
</form>