<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

    <script src="media://js/mootools.js" />

    <script>
        window.addEvent('domready', function() {
            $$('.icon-trash').addEvent('click',function(){
                var id = $(this).getAttribute('data-id');
                var request = new Request.JSON({
                    url: '<?=@route();?>',
                    method: 'delete',
                    data: {
                        action: 'delete',
                        id: id,
                        _token:'<?= @object('user')->getSession()->getToken() ?>'
                    },
//                action: delete,
                    onComplete: function(response){
                        $('comment-'+id).remove()
                    }
                }).send();
            });
        });
    </script>

<?if(@object('com:comments.controller.comment')->canAdd()):?>
    <?= @template('com:comments.view.comment.form.html'); ?>
<?endif;?>

<? foreach($comments as $comment) : ?>
    <div class="comment" id="comment-<?=$comment->id;?>">
        <div class="comment-header">
           <span class="comment-header-author">
                <?= $comment->created_by == @object('user')->id ? @text('You') : $comment->created_by_name ?>&nbsp;<?= @text('wrote') ?>
           </span>
           <span class="comment-header-time">
                <time datetime="<?= $comment->created_on ?>" pubdate><?= @helper('date.humanize', array('date' => $comment->created_on)) ?></time>
            </span>
            <?if($comment->deleteable):?>
                <span class="comment-header-options">
                    <i class="icon-trash" data-id="<?=$comment->id;?>"></i>
                </span>
            <? endif;?>
        </div>
        <div class="comment-content">
            <p><?= @escape($comment->text) ?></p>
        </div>
    </div>
<? endforeach ?>