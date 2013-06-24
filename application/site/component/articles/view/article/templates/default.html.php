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
	    <? if ($article->editable) : ?>
            <button class="btn" onclick='ClickToSave()'>Save</button>
	    <? endif; ?>
	    <h1 id="title" contenteditable="<?= $article->editable ? 'true':'false';?>" onBlur="ClickToSave()"><?= $article->title ?></h1>
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
    <div id="introtext" class="article_introtext" contenteditable="<?= $article->editable ? 'true':'false';?>" onBlur="ClickToSave()">
        <?= $article->introtext ?>
    </div>
    <? else : ?>
    <div id="introtext" contenteditable="<?= $article->editable ? 'true':'false';?>" onBlur="ClickToSave()">
        <?= $article->introtext ?>
    </div>
    <? endif ?>

    <div id="fulltext" contenteditable="<?= $article->editable?  'true':'false';?>" onBlur="ClickToSave()">
    <?= $article->fulltext ?>
    </div>



    
    <?= @template('com:terms.view.terms.default.html') ?>
    <?= @template('com:attachments.view.attachments.default.html', array('attachments' => $attachments, 'exclude' => array($article->image))) ?>
</article>
<? if ($article->editable) : ?>
    <script src="media://application/js/jquery.js" /></script>
    <script type="text/javascript">
    var $jQuery = jQuery.noConflict();
    </script>
    <script src="media:///wysiwyg/ckeditor/ckeditor.js" />
    <script type='text/javascript' language='javascript'>

        function ClickToSave () {
            var introtext = CKEDITOR.instances.introtext.getData();
            var title = CKEDITOR.instances.title.getData();
            var fulltext = CKEDITOR.instances.fulltext.getData();

            jQuery.post('<?=@route();?>', {
                id: <?=$article->id;?>,
                introtext : introtext,
                fulltext : fulltext,
                title : title,
                _token:'<?= @object('user')->getSession()->getToken() ?>'
            })
        }

        // This code is generally not necessary, but it is here to demonstrate
        // how to customize specific editor instances on the fly. This fits well
        // this demo because we have editable elements (like headers) that
        // require less features.

        // The "instanceCreated" event is fired for every editor instance created.
        CKEDITOR.on( 'instanceCreated', function( event ) {
            var editor = event.editor,
                element = editor.element;

            // Customize editors for headers and tag list.
            // These editors don't need features like smileys, templates, iframes etc.
            if ( element.is( 'h1', 'h2', 'h3' ) || element.getAttribute( 'id' ) == 'taglist' ) {
                // Customize the editor configurations on "configLoaded" event,
                // which is fired after the configuration file loading and
                // execution. This makes it possible to change the
                // configurations before the editor initialization takes place.
                editor.on( 'configLoaded', function() {

                    // Remove unnecessary plugins to make the editor simpler.
                    editor.config.removePlugins = 'colorbutton,find,flash,font,' +
                        'forms,iframe,image,newpage,removeformat,' +
                        'smiley,specialchar,stylescombo,templates';

                    // Rearrange the layout of the toolbar.
                    editor.config.toolbarGroups = [
                        { name: 'editing',		groups: [ 'basicstyles', 'links' ] },
                        { name: 'undo' }
                    ];
                });
            }
        });

    </script>
<? endif;?>