<?
/**
 * @package     Nooku_Server
 * @subpackage  Comments
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<div id="comments-comments-list">
	<? foreach($comments as $comment) :?>
	<div class="comment">
		<div class="comment-header">
			<?= $comment->created_by == object('user')->id ? translate('You') : $comment->created_by_name ?>&nbsp;<?= translate('wrote') ?>
			<time datetime="<?= $comment->created_on ?>" pubdate><?= helper('date.humanize', array('date' => $comment->created_on)) ?></time>
		</div>
		<p><?= escape($comment->text) ?></p>
	</div>
	<? endforeach ?>
</div>