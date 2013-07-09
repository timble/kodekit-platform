<?php
/**
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */
?>
<article <?= !$article->published ? 'class="article-unpublished"' : '' ?>>
    <div class="page-header">
	    <h1 id="title" contenteditable="<?= $article->editable ? 'true':'false';?>"><?= $article->title ?></h1>
	    <?= @helper('date.timestamp', array('row' => $article, 'show_modify_date' => false)); ?>
	    <? if (!$article->published) : ?>
	    <span class="label label-info"><?= @text('Unpublished') ?></span>
	    <? endif ?>
	    <? if ($article->access) : ?>
	    <span class="label label-important"><?= @text('Registered') ?></span>
	    <? endif ?>
	</div>

    <? if($article->thumbnail): ?>
        <img class="thumbnail" src="<?= $article->thumbnail ?>" align="right" style="margin:0 0 20px 20px;" />
    <? endif; ?>

    <? if($article->fulltext) : ?>
    <div id="introtext" class="article_introtext" contenteditable="<?= $article->editable ? 'true':'false';?>">
        <?= $article->introtext ?>
    </div>
    <? else : ?>
    <div id="introtext" contenteditable="<?= $article->editable ? 'true':'false';?>" >
        <?= $article->introtext ?>
    </div>
    <? endif ?>

    <div id="fulltext" contenteditable="<?= $article->editable?  'true':'false';?>">
    <?= $article->fulltext ?>
    </div>


    <?= @template('com:tags.view.tags.default.html') ?>
    <?= @template('com:attachments.view.attachments.default.html', array('attachments' => $attachments, 'exclude' => array($article->attachments_attachment_id))) ?>
</article>

<? if ($article->editable) : ?>
    <script src="media://application/js/jquery.js" /></script>

    <script src="media://ckeditor/ckeditor/ckeditor.js" />
    <script type='text/javascript' language='javascript'>

        CKEDITOR.on( 'instanceCreated', function( event ) {
            var editor = event.editor,
                element = editor.element;

            if ( element.is( 'h1', 'h2', 'h3' ) || element.getAttribute( 'id' ) == 'taglist' ) {
                editor.on( 'configLoaded', function() {
                    editor.config.toolbar = 'title';
                });
            }else{
                editor.on( 'configLoaded', function() {
                    editor.config.toolbar = 'standard';
                });
            }
            editor.on('blur', function (ev) {
                jQuery.post('<?=@route();?>', {
                    id: <?=$article->id;?>,
                    introtext : CKEDITOR.instances.introtext.getData(),
                    fulltext : CKEDITOR.instances.fulltext.getData(),
                    title : CKEDITOR.instances.title.getData(),
                    _token:'<?= @object('user')->getSession()->getToken() ?>'
                });
            });
        });

    </script>
<? endif;?>
