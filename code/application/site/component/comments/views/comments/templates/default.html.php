<? foreach($comments as $comment) :?>
<div class="comment">
    <div class="comment-header">
        <?= $comment->created_by == $user->id ? @text('You') : $comment->created_by_name ?>&nbsp;<?= @text('wrote') ?>
        <time datetime="<?= $comment->created_on ?>" pubdate><?= @helper('date.humanize', array('date' => $comment->created_on)) ?></time>
    </div>
    <p><?= @escape($comment->text) ?></p>
</div>
<? endforeach ?>