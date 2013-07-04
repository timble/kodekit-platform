<?
/**
 * @package     Nooku_Server
 * @subpackage  Comments
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<script src="media://js/mootools.js" />


<script src="media:///wysiwyg/ckeditor/ckeditor.js" />
<script type='text/javascript' language='javascript'>

    function ClickToSave (comment_id) {
        var request = new Request.JSON({
            url: '?view=comment&id='+comment_id,
            data: {
                id: comment_id,
                text: CKEDITOR.instances['comment-'+comment_id].getData(),
                _token:'<?= @object('user')->getSession()->getToken() ?>'
            },
            onComplete: function(response){

            }
        }).send();
    }

    CKEDITOR.on( 'instanceCreated', function( event ) {
        var editor = event.editor,

        editor.config.toolbar = 'title';
    });

    window.addEvent('domready', function() {
        $$('.icon-trash').addEvent('click',function(){
            var id = $(this).getAttribute('data-id');
            var request = new Request.JSON({
                url: '?view=comment&id='+id,
                method: 'delete',
                data: {
                    id: id,
                    _token:'<?= @object('user')->getSession()->getToken() ?>'
                },
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
            <?if($comment->created_by == @object('user')->getId() || $this->getUser()->getRole() >= 23):?>
                <span class="comment-header-options">
                    <i class="icon-trash" data-id="<?=$comment->id;?>"></i>
                </span>
            <? endif;?>
        </div>
        <div class="comment-content" id="comment-<?=$comment->id;?>" contenteditable="<?= $comment->editable ? 'true':'true';?>" onBlur="ClickToSave(<?=$comment->id?>)">
            <p><?= @escape($comment->text) ?></p>
        </div>

    </div>

<? endforeach ?>

