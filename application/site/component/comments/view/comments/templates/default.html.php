<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<?if(object('com:comments.controller.comment')->canDelete()):?>
    <?= @helper('behavior.mootools') ?>
    <script src="media://comments/js/comments.js" />

    <script>
        window.addEvent('domready', (function() {
            options = {
                container: 'comments',
                data: {
                    _token: '<?= object('user')->getSession()->getToken() ?>'
                }
            };

            new Comments(options);
        }));
    </script>
<? endif ?>

<?if(object('com:comments.controller.comment')->canAdd()):?>
    <script src="media://files/js/jquery-1.8.0.min.js" />
    <script src="media://ckeditor/ckeditor/ckeditor.js" />

    <script>
        CKEDITOR.on( 'instanceCreated', function( event ) {
            var editor = event.editor;

            editor.config.toolbar = 'title';
            editor.config.removePlugins = 'codemirror,readmore,autosave,images,files';
            editor.config.allowedContent = "";
            editor.config.forcePasteAsPlainText = true;

            editor.on('blur', function(e){
                if (e.editor.checkDirty()){
                    var id = e.editor.name.split("-");

                    jQuery.ajax({
                        type: "POST",
                        url: '?view=comment&id='+id[1],
                        data: {
                            text: e.editor.getData(),
                            _token:'<?= object('user')->getSession()->getToken() ?>'
                        },
                        success: function() {

                        }
                    });
                }
            });
        });
    </script>
<? endif ?>

<? if(count($comments) || object('com:comments.controller.comment')->canAdd()) : ?>
<div id="comments" class="comments">
    <? foreach($comments as $comment) : ?>
        <div class="comment" id="comment-<?=$comment->id;?>">
            <div class="comment-header">
               <span class="comment-header-author">
                    <?= $comment->created_by == object('user')->id ? translate('You') : $comment->created_by_name ?>&nbsp;<?= translate('wrote') ?>
               </span>
               <span class="comment-header-time">
                    <time datetime="<?= $comment->created_on ?>" pubdate><?= helper('date.humanize', array('date' => $comment->created_on)) ?></time>
                </span>
                <?if(object('com:comments.controller.comment')->id($comment->id)->canDelete()):?>
                    <span class="comment-header-options">
                        <i class="icon-trash" data-id="<?=$comment->id;?>"></i>
                    </span>
                <? endif;?>
            </div>

            <div class="comment-content" id="comment-<?=$comment->id;?>" contenteditable="<?= object('com:comments.controller.comment')->id($comment->id)->canEdit() == 1? "true":"false";?>" >
                <?=$comment->text?>
            </div>
        </div>
    <? endforeach ?>
    <?if(object('com:comments.controller.comment')->canAdd()):?>
        <?= include('com:comments.view.comment.form.html'); ?>
    <?endif;?>
</div>
<?endif;?>