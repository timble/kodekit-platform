<script src="media://js/mootools.js" />
<!--
'option=com_comments&view=comment&layout=form&row='.$state->row.'&table='.$state->table.'&Itemid='.$Itemid
-->

<?= @overlay(array('url' => @helper('route.comment',array('layout'=>'form','row'=>$state->row,'table'=>$state->table)))) ?>
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