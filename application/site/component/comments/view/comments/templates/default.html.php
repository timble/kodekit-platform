<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<? if(object('com:comments.controller.comment')->canDelete()) : ?>
    <?= helper('behavior.mootools') ?>
    <ktml:script src="assets://application/js/jquery.js" />
    <ktml:script src="assets://comments/js/comments.js" />

    <script>
        window.addEvent('domready', (function() {
            options = {
                container: 'comments',
                data: {
                    csrf_token: '<?= object('user')->getSession()->getToken() ?>'
                }
            };

            new Comments(options);
        }));
    </script>
<? endif ?>

<? if(object('com:comments.controller.comment')->canAdd()) : ?>
    <ktml:script src="assets://application/js/jquery.js" />
    <ktml:cript src="assets://ckeditor/ckeditor/ckeditor.js" />

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
                            csrf_token:'<?= object('user')->getSession()->getToken() ?>'
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
            <div class="comment__header">
               <span class="comment__header--left">
                    <?= $comment->created_by == object('user')->id ? translate('You') : $comment->getAuthor()->getName() ?>&nbsp;<?= translate('wrote') ?>
               </span>
               <span class="comment__header--right">
                    <time datetime="<?= $comment->created_on ?>" pubdate><?= helper('date.humanize', array('date' => $comment->created_on)) ?></time>
                   <? if(object('com:comments.controller.comment')->id($comment->id)->canDelete()) : ?>
                       <i class="icon-trash" data-id="<?=$comment->id;?>"></i>
                   <? endif;?>
               </span>
            </div>

            <div class="comment__text" id="comment-<?=$comment->id;?>" contenteditable="<?= object('com:comments.controller.comment')->id($comment->id)->canEdit() == 1? "true" : "false"; ?>" >
                <?=$comment->text?>
            </div>
        </div>
    <? endforeach ?>
    <?if(object('com:comments.controller.comment')->canAdd()):?>
        <?= import('com:comments.view.comment.form.html'); ?>
    <?endif;?>
</div>
<? endif; ?>