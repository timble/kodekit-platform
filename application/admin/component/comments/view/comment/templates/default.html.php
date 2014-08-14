<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<?= helper('behavior.keepalive') ?>
<?= helper('behavior.validator') ?>

<ktml:script src="assets://js/koowa.js" />
<ktml:style src="assets://css/koowa.css" />

<ktml:module position="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:module>

<form action="" method="post" id="comment-form" class="-koowa-form">
    <?= object('com:ckeditor.controller.editor')->render(array('name' => 'text', 'toolbar' => 'basic', 'text' => $comment->text)) ?>
</form>