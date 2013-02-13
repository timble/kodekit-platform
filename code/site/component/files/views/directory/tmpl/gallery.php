<?
/**
 * @version     $Id$
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('_JEXEC') or die; ?>

<script src="media://com_files/site/js/jquery-1.8.0.min.js" />
<script src="media://com_files/site/js/bootstrap-modal.js" />
<script src="media://com_files/site/js/bootstrap-image-gallery.js" />
<script src="media://com_files/site/js/gallery.js" />

<script>
jQuery(function($) {
    new ComFiles.Gallery($('div.files-gallery'), {thumbwidth: <?= json_encode((int)$thumbnail_size['x']) ?>});
});
</script>

<div class="com_files files-gallery">
    <div id="modal-gallery" class="modal modal-fullscreen modal-gallery hide fades in">
        <div class="modal-header">
            <a class="close" style="cursor: pointer" data-dismiss="modal">&times;</a>
            <h3 class="modal-title"></h3>
        </div>
        <div class="modal-body"><div class="modal-image"></div></div>
    </div>

    <? if ($params->get('show_page_title', 1)): ?>
	<div class="page-header">
		<h1><?= @escape($params->get('page_title')); ?></h1>
	</div>
	<? endif; ?>
	
	<? if ($page->getLink()->query['folder'] !== $state->folder): ?>
	<h2><?= @escape($state->folder); ?></h2>
	<? endif; ?>

    <? if ($parent !== null): ?>
    <h4>
    	<a href="<?= @route('&view=folder&folder='.$parent) ?>">
    		<?= @text('Parent Folder') ?>
    	</a>
    </h4>
    <? endif ?>

    <? if (count($files) || count($folders)): ?>
    <ol class="gallery-thumbnails" data-toggle="modal-gallery" data-target="#modal-gallery" data-selector="a.files-view">
        <? foreach($folders as $folder): ?>
        <li class="gallery-folder">
            <a href="<?= @route('&view=folder&folder='.$folder->path) ?>">
                <span class="file-thumbnail"></span>
                <span class="file-label"><?= @escape($folder->display_name) ?></span>
            </a>
        </li>
        <? endforeach ?>
        
        <? foreach($files as $file): ?>
    	<? if ($params->get('show_thumbnails') && !empty($file->thumbnail)): ?>
        <li class="gallery-file">
    		<a class="files-view" data-path="<?= @escape($file->path); ?>"
    			href="<?= @route('&view=file&folder='.$state->folder.'&name='.$file->name) ?>"
    		    title="<?= @escape($file->display_name) ?>"
            >
    		    <span class="file-thumbnail" style="min-height:<?= $thumbnail_size['y'] ?>px">
        		    <img class="img-polaroid" src="<?= $file->thumbnail ?>" alt="<?= @escape($file->display_name) ?>" />
        		</span>
        		<? if ($params->get('show_filenames')): ?>
        		<span class="file-label"><?= @escape($file->display_name) ?></span>
        		<? endif; ?>    
        	</a>
        </li>
    	<? else: ?>
        <li class="gallery-document">
    		<a class="files-view" data-path="<?= @escape($file->path); ?>"
    			href="<?= @route('&view=file&folder='.$state->folder.'&name='.$file->name) ?>"
    		    title="<?= @escape($file->display_name) ?>"
            >
    			<span class="file-thumbnail" style="min-height:<?= $thumbnail_size['y'] ?>px"></span>
        		<? if ($params->get('show_filenames')): ?>
        		<span class="file-label"><?= @escape($file->display_name) ?></span>
        		<? endif; ?>
    		</a>
        </li>
    	<? endif ?>
        <? endforeach ?>
    </ol>

    <? if(count($files) != $total): ?>
	    <?= @helper('paginator.pagination', array(
	    	'total' => $total,
	    	'limit' => $state->limit,
	    	'offset' => $state->offset,
	    	'show_count' => false,
	    	'show_limit' => false
	    )) ?>
    <? endif ?>
    
    <? endif ?>
</div>
