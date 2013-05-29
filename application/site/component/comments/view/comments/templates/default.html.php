<? foreach($comments as $comment) :?>
<div class="comment">
    <div class="comment-header">
       <span class="comment-header-author">
            <?= $comment->created_by == @object('user')->id ? @text('You') : $comment->created_by_name ?>&nbsp;<?= @text('wrote') ?>
       </span>
       <span class="comment-header-time">
            <time datetime="<?= $comment->created_on ?>" pubdate><?= @helper('date.humanize', array('date' => $comment->created_on)) ?></time>
        </span>
    </div>
    <div class="comment-content">
        <p><?= @escape($comment->text) ?></p>
    </div>
</div>
<? endforeach ?>