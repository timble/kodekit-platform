<? foreach($comments as $comment) :?>
<div class="comment">
    <div class="comment-header">
<<<<<<< HEAD
       <span class="comment-header-author">
            <?= $comment->created_by == @service('user')->id ? @text('You') : $comment->created_by_name ?>&nbsp;<?= @text('wrote') ?>
       </span>
       <span class="comment-header-time">
            <time datetime="<?= $comment->created_on ?>" pubdate><?= @helper('date.humanize', array('date' => $comment->created_on)) ?></time>
        </span>
    </div>
    <div class="comment-content">
        <p><?= @escape($comment->text) ?></p>
=======
        <?= $comment->created_by == @object('user')->id ? @text('You') : $comment->created_by_name ?>&nbsp;<?= @text('wrote') ?>
        <time datetime="<?= $comment->created_on ?>" pubdate><?= @helper('date.humanize', array('date' => $comment->created_on)) ?></time>
>>>>>>> origin/develop
    </div>
</div>
<? endforeach ?>